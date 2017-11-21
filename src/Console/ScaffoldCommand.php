<?php

namespace Ipunkt\LaravelScaffolding\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

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
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * placeholder collection
	 *
	 * @var \Illuminate\Support\Collection
	 */
	protected $placeholder;

	/**
	 * resources collection
	 *
	 * @var \Illuminate\Support\Collection
	 */
	protected $resources;

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
		parent::__construct();

		$this->placeholder = collect();
		$this->resources = collect();
	}

	/**
	 * Execute the console command.
	 *
	 * @return bool|null
	 */
	public function handle()
	{
		$this->buildPlaceholder();

		$this->buildResources();

		$this->info('Scaffolding ' . $this->getNameInput());

		$created = [];

		$this->resources->each(function ($resourceFileOptions, $resource) use (&$created) {
			// resource can have multiple file stubs
			collect($resourceFileOptions)->each(function ($options) use ($resource, &$created) {
				$stub = $this->verifyStub($options, $resource);
				$target = $this->verifyTarget($options, $resource);
				$this->makeDirectory($target);

				$source = $this->files->get($stub);

				$content = $this->replacePlaceholder($source);
				if (array_get($options, 'append', false)) {
					$existingContent = $this->files->get($target);

					$result = 'skipped';
					if (substr_count($existingContent, $content) === 0) {
						$this->files->append($target, $content);
						$result = 'appended';
					}
				} else {
					$this->files->put($target, $content);
					$result = 'written';
				}

				$targetFile = str_replace(getcwd(), '.', $target);
				$created[] = [
					$resource,
					$targetFile,
					$result,
				];
			});
		});

		$this->table(['Resource', 'File', 'Result'], $created);
	}

	/**
	 * replaces placeholder in string
	 *
	 * @param string $source
	 *
	 * @return string
	 */
	protected function replacePlaceholder(string $source): string
	{
		return str_replace(
			$this->placeholder->keys()->toArray(),
			$this->placeholder->values()->toArray(),
			$source
		);
	}

	/**
	 * Build the directory for the class if necessary.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	protected function makeDirectory(string $path): string
	{
		if ( ! $this->files->isDirectory(dirname($path))) {
			$this->files->makeDirectory(dirname($path), 0777, true, true);
		}

		return $path;
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
	 * builds all placeholder
	 */
	protected function buildPlaceholder()
	{
		$name = $this->getNameInput();
		$namespace = '';
		$suffixedNamespace = '';
		$prefixedNamespace = '';
		$prefixDottedNamespace = '';
		$suffixDottedNamespace = '';

		$namespaceSeparator = "\\";
		if (substr_count($name, $namespaceSeparator)) {
			$parts = explode($namespaceSeparator, $name);
			$name = array_pop($parts);
			$namespace = implode($namespaceSeparator, $parts);
			$prefixedNamespace = $namespaceSeparator . implode($namespaceSeparator, $parts);
			$suffixedNamespace = implode($namespaceSeparator, $parts) . $namespaceSeparator;
			$prefixDottedNamespace = '.' . implode('.', $parts);
			$suffixDottedNamespace = implode('.', $parts) . '.';
		}

		$this->placeholder->put('{{Namespace}}', $namespace);
		$this->placeholder->put('{{\Namespace}}', $prefixedNamespace);
		$this->placeholder->put('{{Namespace\}}', $suffixedNamespace);
		$this->placeholder->put('{{\namespace}}', strtolower($prefixedNamespace));
		$this->placeholder->put('{{namespace\}}', strtolower($suffixedNamespace));
		$this->placeholder->put('{{namespace}}', strtolower($namespace));
		$this->placeholder->put('{{.namespace}}', strtolower($prefixDottedNamespace));
		$this->placeholder->put('{{namespace.}}', strtolower($suffixDottedNamespace));
		$this->placeholder->put('{{Model}}', str_singular($name));
		$this->placeholder->put('{{model}}', strtolower(str_singular($name)));
		$this->placeholder->put('{{Models}}', str_plural($name));
		$this->placeholder->put('{{models}}', strtolower(str_plural($name)));

		$placeholder = config('laravel-scaffolding.placeholder', []);
		foreach ($placeholder as $key => $value) {
			$this->placeholder->put('{{' . $key . '}}', value($value));
		}
	}

	/**
	 * builds all known resources
	 */
	protected function buildResources()
	{
		$resources = config('laravel-scaffolding.resources', []);

		foreach ($resources as $resource => $options) {
			if (array_get($options, '0') === null) {
				$options = [$options];
			}

			$this->resources->put($resource, $options);
		}

		$with = $this->option('with');
		if ( ! empty($with)) {
			$this->resources = $this->resources->filter(function ($options, $resource) use ($with) {
				return in_array(strtolower($resource), $with);
			});
		}

		$except = $this->option('except');
		if ( ! empty($except)) {
			$this->resources = $this->resources->filter(function ($options, $resource) use ($except) {
				return ! in_array(strtolower($resource), $except);
			});
		}
	}

	/**
	 * can it be overwritten?
	 *
	 * @return bool
	 */
	protected function overwrite(): bool
	{
		return $this->option('force');
	}

	/**
	 * verifies stub and returns it
	 *
	 * @param array $options
	 * @param string $resource
	 *
	 * @return string
	 */
	private function verifyStub(array $options, string $resource): string
	{
		$stub = array_get($options, 'stub', null);
		if ($stub === null) {
			throw new \InvalidArgumentException('No stub for ' . $resource . ' configured');
		}

		if ( ! $this->files->exists($stub)) {
			throw new \InvalidArgumentException('Configured stub for ' . $resource . ' not found');
		}

		return $stub;
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
		$target = $this->replacePlaceholder($target);
		$target = str_replace([
			"\\",
		], '/', $target);

		$append = array_get($options, 'append', false);
		if ($append) {
			if ( ! $this->files->exists($target)) {
				$this->files->put($target, '');
			}

			return $target;
		}

		if ($this->files->exists($target) && ! $this->overwrite()) {
			throw new \InvalidArgumentException('Target file already exists');
		}

		return $target;
	}
}
