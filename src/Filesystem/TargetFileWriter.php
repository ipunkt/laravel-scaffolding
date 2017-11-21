<?php

namespace Ipunkt\LaravelScaffolding\Filesystem;

use Illuminate\Filesystem\Filesystem;

class TargetFileWriter
{
	const WRITE_MODE_WRITE = 'write';
	const WRITE_MODE_APPEND = 'append';
	const RESULT_CREATED = 'created';
	const RESULT_APPENDED = 'appended';

	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	private $filesystem;

	protected $writeMode = self::WRITE_MODE_WRITE;

	protected $overwrite = false;

	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}

	public function setMode(string $mode): self
	{
		$this->writeMode = $mode;

		return $this;
	}

	public function overwrite(bool $flag = true): self
	{
		$this->overwrite = $flag === true;

		return $this;
	}

	public function write(string $target, string $content): string
	{
		$writeMode = $this->writeMode;

		if ( ! $this->filesystem->isDirectory(dirname($target))) {
			$this->filesystem->makeDirectory(dirname($target), 0777, true, true);
		}

		if ($writeMode === self::WRITE_MODE_APPEND) {
			if ( ! $this->filesystem->exists($target)) {
				$writeMode = self::WRITE_MODE_WRITE;
			}
		}

		if ($this->filesystem->exists($target) && ! $this->overwrite) {
			throw new \InvalidArgumentException('Target file already exists');
		}

		if ($writeMode === self::WRITE_MODE_APPEND) {
			$this->filesystem->append($target, $content);

			return self::RESULT_APPENDED;
		}

		$this->filesystem->put($target, $content);

		return self::RESULT_CREATED;
	}
}