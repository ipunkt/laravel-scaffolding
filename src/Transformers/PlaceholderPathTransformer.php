<?php

namespace Ipunkt\LaravelScaffolding\Transformers;

use Illuminate\Support\Collection;
use Ipunkt\LaravelScaffolding\Repositories\PlaceholderRepository;

class PlaceholderPathTransformer extends PlaceholderTransformer
{
	/**
	 * @param \Illuminate\Support\Collection $placeholder
	 * @param string $text
	 *
	 * @return string
	 */
	public static function transform(Collection $placeholder, string $text): string
	{
		$text = parent::transform($placeholder, $text);

		return str_replace(
			PlaceholderRepository::NAMESPACE_SEPARATOR,
			DIRECTORY_SEPARATOR,
			$text
		);
	}
}