<?php

namespace Marks\Stockfighter;

use Marks\Stockfighter\Communicators\DefaultAPICommunicator;
use Marks\Stockfighter\Communicators\DefaultWebSocketCommunicator;
use Marks\Stockfighter\Contracts\APICommunicatorContract;
use Marks\Stockfighter\Contracts\WebSocketCommunicatorContract;
use Marks\Stockfighter\Exceptions\StockfighterException;
use Marks\Stockfighter\Paths\Venue;

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
	 * The communicator class used to communicate with the websocket endpoints.
	 * @var WebSocketCommunicatorContract
	 */
	protected $websocket_communicator = null;

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
	 * @param string|bool                   $api_key                Either the API key, or false to use
	 *                                                              the default API key.
	 * @param APICommunicatorContract       $communicator           The API communicator.
	 * @param WebSocketCommunicatorContract $websocket_communicator The WebSocket communicator.
	 *
	 * @throws StockfighterException
	 */
	public function __construct($api_key = false, APICommunicatorContract $communicator = null, WebSocketCommunicatorContract $websocket_communicator = null)
	{
		// Set some defaults.
		$this->communicator = $communicator ? $communicator : new DefaultAPICommunicator($this);
		$this->websocket_communicator = $websocket_communicator ? $websocket_communicator :
			new DefaultWebSocketCommunicator($this);

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
	 * Gets the communicator for the current instance.
	 *
	 * @return APICommunicatorContract
	 */
	public function getCommunicator()
	{
		return $this->communicator;
	}

	/**
	 * Gets the websocket communicator for the current instance.
	 *
	 * @return WebSocketCommunicatorContract
	 */
	public function getWebSocketCommunicator()
	{
		return $this->websocket_communicator;
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

	/**
	 * API path for getting information about a specific venue.
	 *
	 * @param string $venue The name of the venue.
	 *
	 * @return Venue
	 */
	public function venue($venue)
	{
		return new Venue($this, $venue);
	}
}
