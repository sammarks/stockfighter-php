<?php

namespace Marks\Stockfighter\Contracts;

use Marks\Stockfighter\Stockfighter;

interface WebSocketContract
{
	/**
	 * WebSocketContract constructor.
	 *
	 * @param string       $url          The URL for the websocket to connect to.
	 * @param Stockfighter $stockfighter The stockfighter instance.
	 */
	public function __construct($url, Stockfighter $stockfighter);

	/**
	 * Opens the websocket connection.
	 */
	public function connect();

	/**
	 * Sets the receive callback for the websocket connection.
	 *
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function receive(callable $callback);
}
