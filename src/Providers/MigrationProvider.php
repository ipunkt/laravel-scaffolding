<?php

namespace Ipunkt\LaravelPackage\Providers;

use Ipunkt\Laravel\PackageManager\Providers\MigrationServiceProvider;

class MigrationProvider extends MigrationServiceProvider
{
    protected $packagePath = __DIR__ . '/../../';
    
    protected $migrationsFolder = 'database/migrations';
}
