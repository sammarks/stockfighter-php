<?php

namespace Marks\Stockfighter\Objects;

trait HasSymbol
{
	/**
	 * The symbol.
	 * @var Symbol
	 */
	public $symbol;

	/**
	 * Creates the symbol object from the passed JSON.
	 *
	 * @param array $object
	 */
	protected function createSymbol(array $object)
	{
		if (!array_key_exists('symbol', $object)) return;

		$this->symbol = new Symbol();
		$this->symbol->name = $object['symbol'];
	}
}
