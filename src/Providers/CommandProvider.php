<?php

namespace Ipunkt\LaravelPackage\Providers;

use Ipunkt\Laravel\PackageManager\Providers\ArtisanServiceProvider;

class CommandProvider extends ArtisanServiceProvider
{
    protected $commands = [];

    protected $registerOnlyForConsole = true;
}