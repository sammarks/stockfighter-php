<?php

namespace Marks\Stockfighter\Objects;

class Execution extends Object
{
	use HasSymbol;

	/**
	 * The account name.
	 * @var string
	 */
	public $account;

	/**
	 * The venue name.
	 * @var string
	 */
	public $venue;

	/**
	 * The order.
	 * @var Order
	 */
	public $order;

	/**
	 * The standing ID of the execution.
	 * @var int
	 */
	public $standingId;

	/**
	 * The ID of the incoming order.
	 * @var int
	 */
	public $incomingId;

	/**
	 * The price for the order.
	 * @var int
	 */
	public $price;

	/**
	 * The amount of shares filled.
	 * @var int
	 */
	public $filled;

	/**
	 * The time at which the shares were filled.
	 * @var string
	 */
	public $filledAt;

	/**
	 * Whether the order that was on the book is now complete
	 * (before this execution).
	 * @var bool
	 */
	public $standingComplete;

	/**
	 * Whether the incoming order is complete (as of this execution).
	 * @var bool
	 */
	public $incomingComplete;

	public function __construct(array $object)
	{
		parent::__construct($object);

		$this->createSymbol($object);
		$this->order = new Order($object['order']);
	}
}
