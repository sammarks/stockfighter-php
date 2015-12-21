<?php

namespace Marks\Stockfighter\Objects;

class Transaction
{
	/**
	 * The price for the stock.
	 * @var int
	 */
	public $price;

	/**
	 * The number transacted.
	 * @var int
	 */
	public $qty;

	/**
	 * Whether or not the transaction was a buy.
	 * @var bool
	 */
	public $isBuy;
}
