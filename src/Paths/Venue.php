<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Exceptions\StockfighterException;
use Marks\Stockfighter\Objects\Symbol;

class Venue extends ResourcePath
{
	protected $resource_name = 'venues';

	/**
	 * Check to see if a specific venue is available.
	 *
	 * @return bool
	 */
	public function heartbeat()
	{
		try {
			$response = $this->communicator()
				->get($this->endpoint('heartbeat'));
			return $response['ok'];
		} catch (StockfighterException $ex) {
			return false;
		}
	}

	/**
	 * Gets an array of Symbol objects representing the stocks for the
	 * current venue.
	 *
	 * @return Symbol[]
	 */
	public function stocks()
	{
		$response = $this->communicator()->get($this->endpoint('stocks'));
		$symbols = array();
		if (array_key_exists('symbols', $response)) {
			foreach ($response['symbols'] as $symbol) {
				$symbols[] = new Symbol($symbol);
			}
		}

		return $symbols;
	}

	/**
	 * Gets the stock path object.
	 *
	 * @param string $stock The name of the stock.
	 *
	 * @return Stock
	 */
	public function stock($stock)
	{
		return new Stock($this->stockfighter, $stock, $this);
	}
}
