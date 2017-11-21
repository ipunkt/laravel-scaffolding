<?php

namespace Ipunkt\LaravelScaffolding\Console;

use Illuminate\Console\Command;
use Ipunkt\LaravelScaffolding\Repositories\ResourcesRepository;

class ScaffoldShowCommand extends Command
{
	protected $signature = 'scaffold:show
							';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Shows all resources that can be scaffold';
	/**
	 *
	 *
	 * @var \Ipunkt\LaravelScaffolding\Repositories\ResourcesRepository
	 */
	private $resourcesRepository;

	public function __construct(ResourcesRepository $resourcesRepository)
	{
		parent::__construct();

		$this->resourcesRepository = $resourcesRepository;
	}

	/**
	 * Execute the console command.
	 *
	 * @return bool|null
	 */
	public function handle()
	{
		$resources = [];

		foreach ($this->resourcesRepository->resources() as $resource => $options) {
			$resources[] = [$resource];
		}

		$this->table(['Resources'], $resources);
	}

}