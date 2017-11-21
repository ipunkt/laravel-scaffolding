<?php

namespace Ipunkt\LaravelScaffolding\Tests;

use Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository;

class PlaceholderRepositoryTest extends TestCase
{
	/** @test */
	public function it_can_resolve_the_models_name()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('User');

		// ASSERT
		$this->assertEquals('User', $repository->name());
		$this->assertEquals('', $repository->namespace());
	}

	/** @test */
	public function it_can_resolve_the_models_name_with_namespace()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName("App\User");

		// ASSERT
		$this->assertEquals('User', $repository->name());
		$this->assertEquals('App', $repository->namespace());
	}

	/** @test */
	public function it_can_resolve_the_models_name_with_namespace_for_slashes()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('App/User');

		// ASSERT
		$this->assertEquals('User', $repository->name());
		$this->assertEquals('App', $repository->namespace());
	}

	/** @test */
	public function it_can_resolve_the_models_name_with_namespace_for_dots()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('App.User');

		// ASSERT
		$this->assertEquals('User', $repository->name());
		$this->assertEquals('App', $repository->namespace());
	}

	/** @test */
	public function it_can_resolve_the_models_name_with_namespaces_and_lowercased()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('app.models.user');

		// ASSERT
		$this->assertEquals('User', $repository->name());
		$this->assertEquals('App\Models', $repository->namespace());
	}

	/** @test */
	public function it_can_resolve_configured_placeholders()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = new PlaceholderRepository([
			'rootNamespace' => "App\\"
		]);

		// ACT

		// ASSERT
		$this->assertArrayHasKey('{{rootNamespace}}', $repository->placeholder());
	}

	/** @test */
	public function it_can_resolve_name_based_placeholders()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = new PlaceholderRepository();

		// ACT
		$repository->setName('App.user');
		$placeholder = $repository->placeholder();

		// ASSERT
		$this->assertArrayHasKey('{{model}}', $placeholder);
		$this->assertArrayHasKey('{{Model}}', $placeholder);

		$this->assertEquals([
			'{{Namespace}}',
			'{{\Namespace}}',
			'{{Namespace\}}',
			'{{\namespace}}',
			'{{namespace\}}',
			'{{namespace}}',
			'{{.namespace}}',
			'{{namespace.}}',
			'{{Model}}',
			'{{model}}',
			'{{Models}}',
			'{{models}}',
		], $placeholder->keys()->all());

		$this->assertEquals([
			'App',
			'\App',
			"App\\",
			'\app',
			"app\\",
			'app',
			'.app',
			'app.',
			'User',
			'user',
			'Users',
			'users',
		], $placeholder->values()->all());
	}

	/** @test */
	public function it_can_resolve_name_based_placeholders_without_namespace()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = new PlaceholderRepository();

		// ACT
		$repository->setName('user');
		$placeholder = $repository->placeholder();

		// ASSERT
		$this->assertArrayHasKey('{{model}}', $placeholder);
		$this->assertArrayHasKey('{{Model}}', $placeholder);

		$this->assertEquals([
			'{{Namespace}}',
			'{{\Namespace}}',
			'{{Namespace\}}',
			'{{\namespace}}',
			'{{namespace\}}',
			'{{namespace}}',
			'{{.namespace}}',
			'{{namespace.}}',
			'{{Model}}',
			'{{model}}',
			'{{Models}}',
			'{{models}}',
		], $placeholder->keys()->all());

		$this->assertEquals([
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'User',
			'user',
			'Users',
			'users',
		], $placeholder->values()->all());
	}
}