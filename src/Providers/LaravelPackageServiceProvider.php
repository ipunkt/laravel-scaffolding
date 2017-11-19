<?php

namespace Ipunkt\LaravelScaffolding\Providers;

use Illuminate\Support\AggregateServiceProvider;

class LaravelScaffoldingServiceProvider extends AggregateServiceProvider
{
	const NAMESPACE = 'laravel-scaffolding';

	/**
	 * The provider class names.
	 *
	 * @var array
	 */
	protected $providers = [
		ConfigurationProvider::class,
		CommandProvider::class,
	];
}