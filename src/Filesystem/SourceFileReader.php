<?php

namespace Ipunkt\LaravelScaffolding\Filesystem;

use Illuminate\Filesystem\Filesystem;

class SourceFileReader
{
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	private $filesystem;

	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}

	public function read(array $options): StubFile
	{
		$stub = array_get($options, 'stub', null);
		if ($stub === null) {
			throw new \InvalidArgumentException('No stub configured');
		}

		if ( ! $this->filesystem->exists($stub)) {
			throw new \InvalidArgumentException('Stub file not found');
		}

		return new StubFile($this->filesystem->get($stub));
	}
}