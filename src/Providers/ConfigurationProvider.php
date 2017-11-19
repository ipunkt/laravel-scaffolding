<?php

namespace Ipunkt\LaravelPackage\Providers;

use Ipunkt\Laravel\PackageManager\Providers\ConfigurationServiceProvider;

class ConfigurationProvider extends ConfigurationServiceProvider
{
    protected $packagePath = __DIR__ . '/../../';

    protected $configurationFiles = [
        LaravelPackageServiceProvider::NAMESPACE => 'config/config.php',
    ];
}
