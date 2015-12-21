<?php

namespace Marks\Stockfighter\Objects;

class Order extends Object
{
	use HasSymbol;

	const DIRECTION_BUY = 'buy';
	const DIRECTION_SELL = 'sell';

	const ORDER_LIMIT = 'limit';
	const ORDER_MARKET = 'market';
	const ORDER_FOK = 'fill-or-kill';
	const ORDER_IOC = 'immediate-or-cancel';

	/**
	 * The name of the venu.
	 * @var string
	 */
	public $venue;

	/**
	 * The direction of the sale (either Object::DIRECTION_BUY or Object::DIRECTION_SELL).
	 * @var string
	 */
	public $direction;

	/**
	 * The original quantity of the order.
	 * @var int
	 */
	public $originalQty;

	/**
	 * This is the quantity *left outstanding*
	 * @var int
	 */
	public $qty;

	/**
	 * The price of the order -- may not match that of fills!
	 * @var int
	 */
	public $price;

	/**
	 * The type of order (any of the type constants on this class).
	 * @var string
	 */
	public $type;

	/**
	 * The guaranteed unique (on this venue) ID of the order.
	 * @var int
	 */
	public $id;

	/**
	 * The name of the account.
	 * @var string
	 */
	public $account;

	/**
	 * The ISO-8601 timestamp for when we received the order.
	 * @var string
	 */
	public $ts;

	/**
	 * The fills for the order. May have zero or multiple fills.
	 * @var Fill[]
	 */
	public $fills;

	/**
	 * The total number filled.
	 * @var int
	 */
	public $totalFilled;

	/**
	 * @var bool
	 */
	public $open;

	public function __construct(array $object)
	{
		parent::__construct($object);

		$this->createSymbol($object);
		$this->fills = $this->getChildren($object['fills'], Fill::class);
	}
}
