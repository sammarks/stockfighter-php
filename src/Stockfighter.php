<?php

namespace Marks\Stockfighter;

use Marks\Stockfighter\Communicators\DefaultAPICommunicator;
use Marks\Stockfighter\Contracts\APICommunicatorContract;
use Marks\Stockfighter\Exceptions\StockfighterException;

class Stockfighter
{
	/**
	 * The default API key used whe connecting to the server.
	 * @var string
	 */
	protected static $default_api_key = '';

	/**
	 * The API key used when connecting to the server (instance-based).
	 * @var string
	 */
	protected $api_key = '';

	/**
	 * The communicator class used to communicate with the API.
	 * @var APICommunicatorContract
	 */
	protected $communicator = null;

	/**
	 * Sets the API key used for later instancing of the library.
	 *
	 * @param string $default_api_key The API key.
	 */
	public static function setApiKey($default_api_key)
	{
		self::$default_api_key = $default_api_key;
	}

	/**
	 * Stockfighter constructor.
	 *
	 * @param string|bool             $api_key      Either the API key, or false to use
	 *                                              the default API key.
	 * @param APICommunicatorContract $communicator The API communicator.
	 *
	 * @throws StockfighterException
	 */
	public function __construct($api_key = false, APICommunicatorContract $communicator = null)
	{
		// Set some defaults.
		$this->communicator = $communicator ? $communicator : new DefaultAPICommunicator($this);

		if (!$api_key) {
			$this->api_key = self::$default_api_key;
		}

		if (!$this->api_key) {
			throw new StockfighterException('You must supply an API key to begin connecting to the API.');
		}
	}

	/**
	 * Gets the instance API key.
	 *
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->api_key;
	}

	/**
	 * Calls the heartbeat endpoint of the API.
	 *
	 * @return bool
	 */
	public function heartbeat()
	{
		try {
			$response = $this->communicator->get('heartbeat');
			return $response['ok'];
		} catch (StockfighterException $ex) {
			return false;
		}
	}
}
