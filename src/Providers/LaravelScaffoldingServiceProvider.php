<?php

namespace Ipunkt\LaravelScaffolding\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Ipunkt\LaravelScaffolding\Console\ScaffoldCommand;
use Ipunkt\LaravelScaffolding\Console\ScaffoldShowCommand;

class LaravelScaffoldingServiceProvider extends ServiceProvider
{
	const NAMESPACE = 'laravel-scaffolding';

	protected $commands = [
		ScaffoldCommand::class,
		ScaffoldShowCommand::class,
	];

	protected $registerOnlyForConsole = true;

	protected $packagePath = __DIR__ . '/../../';

	public function register()
	{
		$this->mergeConfigFrom(
			$this->packagePath('config/config.php'), self::NAMESPACE
		);

		if ($this->app->runningInConsole()) {
			$this->publishes([
				$this->packagePath('resources/stubs') => resource_path('stubs'),
			], 'scaffolding-stubs');

			$this->publishes([
				$this->packagePath('config/config.php') => config_path(self::NAMESPACE . '.php'),
			], 'scaffolding-config');
		}

		$this->registerCommands($this->commands);
	}

	/**
	 * registers an array of commands (key is short name and value is command class
	 * or part of the registerYYYCommand method name)
	 *
	 * @param array $commands
	 *
	 * @throws \Exception
	 */
	protected function registerCommands(array $commands)
	{
		if ( ! $this->app->runningInConsole()) {
			return;
		}

		foreach ($commands as $key => $command) {
			if (is_numeric($key)) {
				$key = Str::lower(str_replace("\\", '.', $command));
			}

			$method = "register{$command}";

			try {
				if (method_exists($this, $method)) {
					call_user_func_array([$this, $method], [$key]);
				} else {
					$this->app->singleton($key, function ($app) use ($command) {
						return $app->make($command);
					});
				}
				$this->commands($key);
			} catch (\Exception $e) {
				throw $e;
			}
		}
	}

	/**
	 * give relative path from package root and return absolute path
	 *
	 * @param string $relativePath
	 *
	 * @return string
	 */
	private function packagePath(string $relativePath): string
	{
		$packagePath = rtrim(str_replace('/', DIRECTORY_SEPARATOR, $this->packagePath), DIRECTORY_SEPARATOR);
		$relativePath = ltrim(str_replace('/', DIRECTORY_SEPARATOR, $relativePath), DIRECTORY_SEPARATOR);

		return realpath($packagePath . DIRECTORY_SEPARATOR . $relativePath);
	}
}