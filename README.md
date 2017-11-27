# Scaffolding for your project

[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-scaffolding/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-scaffolding/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![License](https://poser.pugx.org/ipunkt/laravel-scaffolding/license.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![Total Downloads](https://poser.pugx.org/ipunkt/laravel-scaffolding/downloads.svg)](https://packagist.org/packages/ipunkt/laravel-scaffolding) [![Build Status](https://travis-ci.org/ipunkt/laravel-scaffolding.svg?branch=master)](https://travis-ci.org/ipunkt/laravel-scaffolding)

This laravel package can scaffold a new resource within seconds. It uses all project-based template stubs to get your project-based setup within the stubs.

You provide your type of scaffolding by providing your stubs. We seed the first stubs with the package publishing. Afterwards you can modify all stubs to suit your needs. After configuring your stubs they will automatically be generated.

This boosts your development time a lot.

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

The `stub` represents a text file with placeholders. The `target` property can handle placeholders and resolves the directory a resource gets saved to. Namespace separator will be automatically replaced by a directory separator.

### Placeholder Configuration

You can add necessary placeholders yourself. Simply add your keys and a callback as value. The callback gets resolved by calling `value()` function with it internally.

Within the stubs and target values you can use them with the prefix `{{` and the suffix `}}` surrounded.

These placeholders are provided internally:

| Placeholder | Value for Resource `User` | Value for Resource `Administration\User` |
| --- | --- | --- |
| `{{Namespace}}` | empty string | `Administration` |
| `{{\Namespace}}` | empty string | `\Administration` |
| `{{Namespace\}}` | empty string | `Administration\` |
| `{{namespace}}` | empty string | `administration` |
| `{{\namespace}}` | empty string | `\administration` |
| `{{namespace\}}` | empty string | `administration\` |
| `{{.namespace}}` | empty string | `.administration` |
| `{{namespace.}}` | empty string | `administration.` |
| `{{Model}}` | `User` | `User` |
| `{{model}}` | `user` | `user` |
| `{{Models}}` | `Users` | `Users` |
| `{{models}}` | `users` | `users` |

| Placeholder | Value for Resource `UserModel` |
| --- | --- |
| `{{Model}}` | `UserModel` |
| `{{model}}` | `user-model` |
| `{{Models}}` | `UserModels` |
| `{{models}}` | `user-models` |

## Usage

First you have to publish the scaffold stubs.

Then you can scaffold your first resource:
```bash
php artisan scaffold ModelName
```

### Command parameter

You can force the creation with overwriting already existing files with the option `--force`.

If you wan only some resources to be generated use `--with` or `--except` for each resource type you want to include or exclude. Each parameter can be used multiple with lowercased resource type names like `model`, `controller` and so on.

### Show resource types configured

You can display all configured resource types with this command:
```bash
php artisan scaffold:show
```
