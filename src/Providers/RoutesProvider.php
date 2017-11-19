<?php

namespace Ipunkt\LaravelPackage\Providers;

use Ipunkt\Laravel\PackageManager\Providers\RouteServiceProvider;

class RoutesProvider extends RouteServiceProvider
{
    protected $packagePath = __DIR__ . '/../../';

    protected $routesNamespace = 'Ipunkt\\LaravelPackage\\Http\\Controllers';

    protected $routesMiddleware = 'web';

    protected $routesFile = 'routes/web.php';
}
