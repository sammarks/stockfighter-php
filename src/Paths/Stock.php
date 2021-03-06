<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Exceptions\StockfighterRequestException;
use Marks\Stockfighter\Objects\Order;
use Marks\Stockfighter\Objects\Quote;

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
	 * Creates a new order with the specified parameters (asynchronously).
	 *
	 * @param string $account
	 * @param int    $price
	 * @param int    $quantity
	 * @param string $direction
	 * @param string $order_type
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	public function orderAsync($account, $price, $quantity, $direction = Order::DIRECTION_BUY, $order_type = Order::ORDER_LIMIT)
	{
		return $this->communicator()
			->postAsync($this->endpoint('orders'), [
				'account' => $account,
				'price' => $price,
				'qty' => $quantity,
				'direction' => $direction,
				'orderType' => $order_type,
			])->then(function (array $body) {
				return new Order($body);
			});
	}

	/**
	 * Gets a quote for the current stock object.
	 *
	 * @return Quote
	 */
	public function quote()
	{
		$response = $this->communicator()
			->get($this->endpoint('quote'));

		return new Quote($response);
	}
}
