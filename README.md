# Scaffolding for your project

[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-scaffolding/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-scaffolding/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![License](https://poser.pugx.org/ipunkt/laravel-scaffolding/license.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![Total Downloads](https://poser.pugx.org/ipunkt/laravel-scaffolding/downloads.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding)

This laravel package can scaffold a new resource within seconds. It uses all project-based template stubs to get your project-based setup within the stubs.

## Quickstart

```
composer require --dev ipunkt/laravel-scaffolding
```

We support package auto-discovery for laravel, so you are ready to use the package.


## Installation

Add to your composer.json following lines

	"require-dev": {
		"ipunkt/laravel-scaffolding": "*"
	}

You can publish all provided files by typing `php artisan vendor:publish` and select the `LaravelScaffoldingServiceProvider`. We also provide tags `scaffolding-config` and `scaffolding-stubs` for the corresponding resources.

## Configuration

The main configuration is divided into two parts: `resources` and `placeholder`.

### Resources Configuration

Here is a full example resource configuration:
```php
/**
 * Resource Model
 */
'Model' => [
	'stub' => resource_path('stubs/model.stub'),
	'target' => app_path('Models/{{Model}}.php'),
	'append' => false, // optional, false by default
],
```

Foreach resource you have to configure one or more sets of an array with the keys `stub` (the stub file), `target` (transformed file will be saved there) and optional `append` to configure whether the file gets appended or created.

The `stub` represents a text file with placeholders. The `target` property can handle placeholders and resolves the directory a resource gets saved to.

### Placeholder Configuration


## Usage

You can use the package like so...
