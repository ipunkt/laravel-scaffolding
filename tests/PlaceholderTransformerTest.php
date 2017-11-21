<?php

namespace Ipunkt\LaravelScaffolding\Tests;

use Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository;
use Ipunkt\LaravelScaffolding\Transformers\PlaceholderTransformer;

class PlaceholderTransformerTest extends TestCase
{
	/** @test */
	public function it_can_replace_placeholder_in_given_string()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('User');

		$actual = PlaceholderTransformer::transform($repository->placeholder(), 'This {{model}}.');

		// ASSERT
		$this->assertEquals('This user.', $actual);
	}

	/** @test */
	public function it_can_replace_placeholder_in_given_string_with_namespaces_too()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('App/User');

		$actual = PlaceholderTransformer::transform($repository->placeholder(), 'This {{Namespace\}} {{Models}}.');

		// ASSERT
		$this->assertEquals('This App\ Users.', $actual);
	}

	/** @test */
	public function it_can_replace_placeholder_in_given_string_without_namespaces_too()
	{
		// ARRANGE
		/** @var PlaceholderRepository $repository */
		$repository = resolve(PlaceholderRepository::class);

		// ACT
		$repository->setName('User');

		$actual = PlaceholderTransformer::transform($repository->placeholder(), 'This {{Namespace\}}{{Model}}.');

		// ASSERT
		$this->assertEquals('This User.', $actual);
	}
}