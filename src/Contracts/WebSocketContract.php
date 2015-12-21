<?php

namespace Marks\Stockfighter\Contracts;

interface WebSocketContract
{
	/**
	 * WebSocketContract constructor.
	 *
	 * @param string $url The URL for the websocket to connect to.
	 */
	public function __construct($url);

	/**
	 * Opens the websocket connection.
	 */
	public function connect();
}
