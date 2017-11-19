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
			'target' => database_path('factories/{{Model}}Factory.php'),
		],

		/**
		 * Resource Controller
		 */
		'Controller' => [
			'stub' => resource_path('stubs/controller.stub'),
			'target' => app_path('Http/Controllers/{{Models}}Controller.php'),
		],

		/**
		 * Form Requests
		 */
		'Request' => [
			[
				'stub' => resource_path('stubs/create-request.stub'),
				'target' => app_path('Http/Requests/Create{{Model}}Request.php'),
			],
			[
				'stub' => resource_path('stubs/update-request.stub'),
				'target' => app_path('Http/Requests/Update{{Model}}Request.php'),
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
		}
	],
];