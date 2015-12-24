<?php

namespace Marks\Stockfighter\Objects;

class Stock extends Object
{
	use HasSymbol;

	/**
	 * The name of the venue.
	 * @var string
	 */
	public $venue;

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
		if (array_key_exists('bids', $object)) {
			$this->bids = $this->getChildren($object['bids'], Transaction::class);
		}
		if (array_key_exists('asks', $object)) {
			$this->asks = $this->getChildren($object['asks'], Transaction::class);
		}
	}
}
