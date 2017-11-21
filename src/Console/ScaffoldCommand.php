<?php

namespace Ipunkt\LaravelScaffolding\Console;

use Illuminate\Console\Command;
use Ipunkt\LaravelScaffolding\Filesystem\SourceFileReader;
use Ipunkt\LaravelScaffolding\Filesystem\TargetFileWriter;
use Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository;
use Ipunkt\LaravelScaffolding\Repositories\ResourcesRepository;
use Ipunkt\LaravelScaffolding\Transformers\PlaceholderPathTransformer;
use Ipunkt\LaravelScaffolding\Transformers\PlaceholderTransformer;

class ScaffoldCommand extends Command
{
	protected $signature = 'scaffold
							{ name : The name of the resource (Model or Namespace\Model) }
							{ --with=* : Only generate with this resource types }
							{ --except=* : Only generate resources without following }
							{ --force : Overwrite existing files }
							';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create various resource files';

	/**
	 * @var \Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository
	 */
	private $placeholderRepository;

	/**
	 * @var \Ipunkt\LaravelScaffolding\Repositories\ResourcesRepository
	 */
	private $resourcesRepository;

	/**
	 * @var \Ipunkt\LaravelScaffolding\Filesystem\SourceFileReader
	 */
	private $sourceFileReader;

	/**
	 * @var \Ipunkt\LaravelScaffolding\Filesystem\TargetFileWriter
	 */
	private $targetFileWriter;

	public function __construct(
		PlaceholderRepository $placeholderRepository,
		ResourcesRepository $resourcesRepository,
		SourceFileReader $sourceFileReader,
		TargetFileWriter $targetFileWriter
	) {
		parent::__construct();

		$this->placeholderRepository = $placeholderRepository;
		$this->resourcesRepository = $resourcesRepository;
		$this->sourceFileReader = $sourceFileReader;
		$this->targetFileWriter = $targetFileWriter;
	}

	/**
	 * Execute the console command.
	 *
	 * @return bool|null
	 */
	public function handle()
	{
		$name = $this->getNameInput();
		$this->info('Scaffolding ' . $name);

		$this->placeholderRepository->setName($name);

		$this->resourcesRepository
			->include($this->option('with'))
			->exclude($this->option('except'));

		$this->targetFileWriter->overwrite($this->option('force'));

		$created = [];

		$this->resourcesRepository->resources()->each(
			function ($resourceFileOptions, $resource) use (&$created) {
				// resource can have multiple file stubs
				collect($resourceFileOptions)->each(
					function ($options) use ($resource, &$created) {
						$stubFile = $this->sourceFileReader->read($options);
						$targetFile = $this->verifyTarget($options, $resource);

						$content = PlaceholderTransformer::transform(
							$this->placeholderRepository->placeholder(),
							$stubFile->content()
						);

						$this->targetFileWriter->setMode(
							array_get($options, 'append', false)
								? TargetFileWriter::WRITE_MODE_APPEND
								: TargetFileWriter::WRITE_MODE_WRITE
						);

						$result = $this->targetFileWriter->write($targetFile, $content);

						$targetFile = str_replace(getcwd(), '.', $targetFile);
						$created[] = [
							$resource,
							$targetFile,
							$result,
						];
					});
			}
		);

		$this->table(['Resource', 'File', 'Result'], $created);

		return true;
	}

	/**
	 * Get the desired class name from the input.
	 *
	 * @return string
	 */
	protected function getNameInput()
	{
		return trim($this->argument('name'));
	}

	/**
	 * verifies target and returns it
	 *
	 * @param array $options
	 * @param string $resource
	 *
	 * @return string
	 */
	private function verifyTarget(array $options, string $resource): string
	{
		$target = array_get($options, 'target', null);
		if ($target === null) {
			throw new \InvalidArgumentException('No target for ' . $resource . ' configured');
		}

		return PlaceholderPathTransformer::transform($this->placeholderRepository->placeholder(), $target);
	}
}
