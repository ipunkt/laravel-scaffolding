<?php

namespace Ipunkt\LaravelScaffolding\Console;

use Illuminate\Console\Command;

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
	 * Execute the console command.
	 *
	 * @return bool|null
	 */
	public function handle()
	{
		$configuredResources = config('laravel-scaffolding.resources', []);
		$resources = [];

		foreach ($configuredResources as $resource => $options) {
			$resources[] = [$resource];
		}

		$this->table(['Resources'], $resources);
	}

}