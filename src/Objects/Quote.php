<?php

namespace Marks\Stockfighter\Objects;

class Quote extends Object
{
	use HasSymbol;

	/**
	 * The name of the venue.
	 * @var string
	 */
	public $venue;

	/**
	 * Best price currently bid for the stock.
	 * @var int
	 */
	public $bid;

	/**
	 * Best price currently offered for the stock.
	 * @var int
	 */
	public $ask;

	/**
	 * Aggregate size of all orders at the best bid.
	 * @var int
	 */
	public $bidSize;

	/**
	 * Aggregate size of all orders at the best ask.
	 * @var int
	 */
	public $askSize;

	/**
	 * Aggregate size of *all bids*
	 * @var int
	 */
	public $bidDepth;

	/**
	 * Aggregate size of *all asks*
	 * @var int
	 */
	public $askDepth;

	/**
	 * Price of the last trade.
	 * @var int
	 */
	public $last;

	/**
	 * Quantity of the last trade.
	 * @var int
	 */
	public $lastSize;

	/**
	 * Timestamp of the last trade.
	 * @var string
	 */
	public $lastTrade;

	/**
	 * Server timestamp of quote generation.
	 * @var string
	 */
	public $quoteTime;

	public function __construct(array $object)
	{
		parent::__construct($object);
		$this->createSymbol($object);
	}
}
