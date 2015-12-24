<?php

namespace Marks\Stockfighter\Objects;

class Fill extends Object
{
	/**
	 * The price for the fill.
	 * @var int
	 */
	public $price;

	/**
	 * The quantity of the fill.
	 * @var int
	 */
	public $qty;

	/**
	 * The timestamp for the fill.
	 * @var string
	 */
	public $ts;
}
