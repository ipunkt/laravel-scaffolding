<?php

namespace Ipunkt\LaravelPackage\Providers;

use Illuminate\Support\AggregateServiceProvider;

class LaravelPackageServiceProvider extends AggregateServiceProvider
{
    const NAMESPACE = 'my-package';

    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ConfigurationProvider::class,
        MigrationProvider::class,
        RoutesProvider::class,
        TranslationProvider::class,
        ViewProvider::class,
        CommandProvider::class,
    ];
}