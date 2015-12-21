<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Objects\Order;

class Stock extends ResourcePath
{
	protected $resource_name = 'stocks';

	/**
	 * Gets the current stock object.
	 *
	 * @return \Marks\Stockfighter\Objects\Stock
	 */
	public function info()
	{
		$response = $this->communicator()->get($this->endpoint(''));
		return new \Marks\Stockfighter\Objects\Stock($response);
	}

	/**
	 * Creates a new order with the specified parameters.
	 *
	 * @param string $account
	 * @param int    $price
	 * @param int    $quantity
	 * @param string $direction
	 * @param string $order_type
	 *
	 * @return Order
	 */
	public function order($account, $price, $quantity, $direction = Order::DIRECTION_BUY, $order_type = Order::ORDER_LIMIT)
	{
		$response = $this->communicator()
			->post($this->endpoint('orders'), [
				'account' => $account,
				'price' => $price,
				'qty' => $quantity,
				'direction' => $direction,
				'orderType' => $order_type,
			]);
		return new Order($response);
	}

	/**
	 * Gets a quote for the current stock object.
	 *
	 * @return \Marks\Stockfighter\Objects\Stock
	 */
	public function quote()
	{
		$response = $this->communicator()
			->get($this->endpoint('quote'));

		return new \Marks\Stockfighter\Objects\Quote($response);
	}
}
