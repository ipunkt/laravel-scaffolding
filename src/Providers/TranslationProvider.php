<?php

namespace Ipunkt\LaravelPackage\Providers;

use Ipunkt\Laravel\PackageManager\Providers\TranslationServiceProvider;

class TranslationProvider extends TranslationServiceProvider
{
    protected $packagePath = __DIR__ . '/../../';

    protected $namespace = LaravelPackageServiceProvider::NAMESPACE;

    protected $translationsFolder = 'resources/lang';
}
