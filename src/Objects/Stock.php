<?php

namespace Marks\Stockfighter\Objects;

class Stock extends Object
{
	/**
	 * The name of the venue.
	 * @var string
	 */
	public $venue;

	/**
	 * The related symbol.
	 * @var Symbol
	 */
	public $symbol;

	/**
	 * The bids associated with the stock.
	 * @var Transaction[]
	 */
	public $bids;

	/**
	 * The asks associated with the stock.
	 * @var Transaction[]
	 */
	public $asks;

	/**
	 * The timestamp we grabbed the book at.
	 * @var string
	 */
	public $ts;

	public function __construct(array $object)
	{
		parent::__construct($object);

		$this->createSymbol($object);
		$this->bids = $this->getChildren($object['bids'], Transaction::class);
		$this->asks = $this->getChildren($object['asks'], Transaction::class);
	}

	/**
	 * Creates the symbol object from the passed JSON.
	 *
	 * @param array $object
	 */
	protected function createSymbol(array $object)
	{
		$this->symbol = new Symbol();
		$this->symbol->name = $object['symbol'];
	}
}
