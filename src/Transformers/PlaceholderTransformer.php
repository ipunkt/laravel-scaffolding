<?php

namespace Ipunkt\LaravelScaffolding\Transformers;

use Illuminate\Support\Collection;

class PlaceholderTransformer
{
	/**
	 * @param \Illuminate\Support\Collection $placeholder
	 * @param string $text
	 *
	 * @return string
	 */
	public static function transform(Collection $placeholder, string $text): string
	{
		return str_replace(
			$placeholder->keys()->toArray(),
			$placeholder->values()->toArray(),
			$text
		);
	}
}