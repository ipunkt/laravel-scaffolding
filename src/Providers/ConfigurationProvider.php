<?php

namespace Ipunkt\LaravelScaffolding\Providers;

use Ipunkt\Laravel\PackageManager\Providers\ConfigurationServiceProvider;

class ConfigurationProvider extends ConfigurationServiceProvider
{
	protected $packagePath = __DIR__ . '/../../';

	protected $configurationFiles = [
		LaravelScaffoldingServiceProvider::NAMESPACE => 'config/config.php',
	];
}
