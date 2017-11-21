<?php

namespace Ipunkt\LaravelScaffolding\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlaceholderRepository
{
	const NAMESPACE_SEPARATOR = "\\";

	/**
	 * placeholder collection
	 *
	 * @var \Illuminate\Support\Collection
	 */
	protected $placeholder;

	/**
	 * model name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * model namespace
	 *
	 * @var string
	 */
	protected $namespace = '';

	public function __construct(array $configuredPlaceholders = null)
	{
		$this->placeholder = collect();

		collect($configuredPlaceholders)->each(function ($value, string $key) {
			$this->addPlaceholder($key, $value);
		});
	}

	/**
	 * setting the name, including the namespace
	 *
	 * @param string $name
	 *
	 * @return \Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository
	 */
	public function setName(string $name): self
	{
		$parts = collect(preg_split('~\W~', $name))->map(function ($part) {
			return Str::studly($part);
		});
		$this->name = $parts->pop();
		$this->namespace = implode(static::NAMESPACE_SEPARATOR, $parts->all());

		$this->addNameBasedPlaceholder();

		return $this;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function namespace(): string
	{
		return $this->namespace;
	}

	public function placeholder(): Collection
	{
		return $this->placeholder;
	}

	public function addPlaceholder(string $key, $value): self
	{
		$this->placeholder->put('{{' . $key . '}}', value($value));

		return $this;
	}

	private function addNameBasedPlaceholder()
	{
		$name = $this->name();
		$namespace = $this->namespace();
		$prefixedNamespace = $namespace === '' ? '' : static::NAMESPACE_SEPARATOR . $namespace;
		$suffixedNamespace = $namespace === '' ? '' : $namespace . static::NAMESPACE_SEPARATOR;
		$prefixDottedNamespace = $namespace === '' ? '' : '.' . $namespace;
		$suffixDottedNamespace = $namespace === '' ? '' : $namespace . '.';

		$this->addPlaceholder('Namespace', $namespace);
		$this->addPlaceholder("\\Namespace", $prefixedNamespace);
		$this->addPlaceholder("Namespace\\", $suffixedNamespace);
		$this->addPlaceholder("\\namespace", strtolower($prefixedNamespace));
		$this->addPlaceholder("namespace\\", strtolower($suffixedNamespace));
		$this->addPlaceholder('namespace', strtolower($namespace));
		$this->addPlaceholder('.namespace', strtolower($prefixDottedNamespace));
		$this->addPlaceholder('namespace.', strtolower($suffixDottedNamespace));
		$this->addPlaceholder('Model', str_singular($name));
		$this->addPlaceholder('model', strtolower(str_singular($name)));
		$this->addPlaceholder('Models', str_plural($name));
		$this->addPlaceholder('models', strtolower(str_plural($name)));
	}
}