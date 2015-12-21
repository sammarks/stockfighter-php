<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Exceptions\StockfighterException;
use Marks\Stockfighter\Exceptions\StockfighterRequestException;
use Marks\Stockfighter\Objects\Symbol;
use Marks\Stockfighter\Stockfighter;

class Venue extends Path
{
	/**
	 * The name of the venue.
	 * @var string|bool
	 */
	protected $venue = false;

	/**
	 * Venue constructor.
	 *
	 * @param Stockfighter $stockfighter
	 * @param string       $venue The name of the venue.
	 */
	public function __construct(Stockfighter $stockfighter, $venue)
	{
		parent::__construct($stockfighter);
		$this->venue = $venue;
	}

	protected function getPathPrefix()
	{
		$prefix = parent::getPathPrefix();
		$prefix .= 'venues/' . $this->venue . '/';

		return $prefix;
	}

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
}
