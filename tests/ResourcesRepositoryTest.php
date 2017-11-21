<?php

namespace Ipunkt\LaravelScaffolding\Tests;

use Ipunkt\LaravelScaffolding\Repositories\ResourcesRepository;

class ResourcesRepositoryTest extends TestCase
{
	/** @test */
	public function it_can_instantiate_the_resource_repository()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = resolve(ResourcesRepository::class);

		// ACT

		// ASSERT
		$this->assertEquals(collect(), $resourceRepository->resources());
	}

	/** @test */
	public function it_can_have_resources_with_one_definition_and_resolves_it_to_a_list()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = new ResourcesRepository([
			'Model' => [
				'stub' => 'stubs/model.stub',
				'target' => 'Models/{{Model}}.php',
			]
		]);

		// ACT

		// ASSERT
		$this->assertEquals(collect([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				]
			]
		]), $resourceRepository->resources());
	}

	/** @test */
	public function it_can_have_resources_with_multiple_definitions()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = new ResourcesRepository([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			]
		]);

		// ACT

		// ASSERT
		$this->assertEquals(collect([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			]
		]), $resourceRepository->resources());
	}

	/** @test */
	public function it_can_filter_with_includes()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = new ResourcesRepository([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
			'Excluded' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
		]);

		// ACT
		$resourceRepository->include(['model']);

		// ASSERT
		$this->assertEquals(collect([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			]
		]), $resourceRepository->resources());
	}

	/** @test */
	public function it_can_filter_with_excludes()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = new ResourcesRepository([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
			'Excluded' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
		]);

		// ACT
		$resourceRepository->exclude(['excluded']);

		// ASSERT
		$this->assertEquals(collect([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			]
		]), $resourceRepository->resources());
	}

	/** @test */
	public function it_can_filter_with_includes_and_excludes()
	{
		// ARRANGE
		/** @var ResourcesRepository $resourceRepository */
		$resourceRepository = new ResourcesRepository([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
			'Excluded' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			],
		]);

		// ACT
		$resourceRepository->include(['model'])->exclude(['excluded']);

		// ASSERT
		$this->assertEquals(collect([
			'Model' => [
				[
					'stub' => 'stubs/model.stub',
					'target' => 'Models/{{Model}}.php',
				],
				[
					'stub' => 'stubs/model2.stub',
					'target' => 'Models/{{Model}}2.php',
				],
			]
		]), $resourceRepository->resources());
	}
}