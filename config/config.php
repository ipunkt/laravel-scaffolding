<?php

return [
	/**
	 * all resources
	 *
	 * [Resource Type] => [
	 *        'stub' => file to stub
	 *        'target' => file to save to with placeholders
	 *        'append' => file append or not, false by default, when not given false
	 * ]
	 */
	'resources' => [
		/**
		 * Resource Model
		 */
		'Model' => [
			'stub' => resource_path('stubs/model.stub'),
			'target' => app_path('Models/{{Model}}.php'),
		],

		/**
		 * Model Factory
		 */
		'Factory' => [
			'stub' => resource_path('stubs/factory.stub'),
			'target' => database_path('factories/{{Namespace}}{{Model}}Factory.php'),
		],

		/**
		 * Database Migration
		 */
		'Migration' => [
			[
				'stub' => resource_path('stubs/create-migration.stub'),
				'target' => database_path('migrations/{{migrationDate}}_create_{{models}}_table.php'),
			],
			[
				'stub' => resource_path('stubs/alter-migration.stub'),
				'target' => database_path('migrations/{{migrationDate}}_alter_{{models}}_table.php'),
			],
		],

		/**
		 * Database Migration
		 */
		'Seeder' => [
			'stub' => resource_path('stubs/seeder.stub'),
			'target' => database_path('seeds/{{Model}}Seeder.php'),
		],

		/**
		 * Resource Controller
		 */
		'Controller' => [
			'stub' => resource_path('stubs/controller.stub'),
			'target' => app_path('Http/Controllers/{{Namespace\}}{{Models}}Controller.php'),
		],

		/**
		 * Form Requests
		 */
		'Request' => [
			[
				'stub' => resource_path('stubs/create-request.stub'),
				'target' => app_path('Http/Requests/{{Namespace\}}Create{{Model}}Request.php'),
			],
			[
				'stub' => resource_path('stubs/update-request.stub'),
				'target' => app_path('Http/Requests/{{Namespace\}}Update{{Model}}Request.php'),
			],
		],

		/**
		 * Resource Route
		 */
		'Route' => [
			[
				'stub' => resource_path('stubs/routes.stub'),
				'target' => base_path('routes/web.php'),
				'append' => true,
			],
		],

		/**
		 * Test Cases
		 */
		'Test' => [
			[
				'stub' => resource_path('stubs/feature-test.stub'),
				'target' => base_path('tests/Feature/{{Namespace\}}{{Model}}Test.php'),
			],
			[
				'stub' => resource_path('stubs/unit-test.stub'),
				'target' => base_path('tests/Unit/{{Namespace\}}{{Model}}Test.php'),
			],
		],
	],

	/**
	 * placeholder map
	 * Key => callback
	 *
	 * builtin placeholders: Model, Models
	 */
	'placeholder' => [
		'rootNamespace' => function () {
			return app()->getNamespace();
		},

		'migrationDate' => function () {
			return date('Y_m_d_His');
		}
	],
];