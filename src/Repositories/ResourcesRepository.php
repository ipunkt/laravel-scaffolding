<?php

namespace Ipunkt\LaravelScaffolding\Repositories;

use Illuminate\Support\Collection;

class ResourcesRepository
{
	/**
	 * @var \Illuminate\Support\Collection
	 */
	protected $resources;

	public function __construct(array $configuredResources = [])
	{
		$this->resources = collect();

		foreach ($configuredResources as $resource => $options) {
			if (array_get($options, '0') === null) {
				$options = [$options];
			}

			$this->resources->put($resource, $options);
		}
	}

	public function resources(): Collection
	{
		return $this->resources;
	}

	public function include(array $resourceTypes): self
	{
		$this->resources = $this->resources->filter(
			function ($options, $resource) use ($resourceTypes) {
				return in_array(strtolower($resource), $resourceTypes);
			}
		);

		return $this;
	}

	public function exclude(array $resourceTypes): self
	{
		$this->resources = $this->resources->filter(
			function ($options, $resource) use ($resourceTypes) {
				return ! in_array(strtolower($resource), $resourceTypes);
			}
		);

		return $this;
	}
}