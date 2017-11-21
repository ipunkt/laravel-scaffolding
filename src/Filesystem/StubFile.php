<?php

namespace Ipunkt\LaravelScaffolding\Filesystem;

class StubFile
{
	/**
	 * @var string
	 */
	private $content;

	public function __construct(string $content)
	{
		$this->content = $content;
	}

	public function content(): string
	{
		return $this->content;
	}
}