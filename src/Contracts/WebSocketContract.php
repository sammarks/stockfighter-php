<?php

namespace Marks\Stockfighter\Contracts;

use Evenement\EventEmitterInterface;
use Marks\Stockfighter\Stockfighter;

interface WebSocketContract extends EventEmitterInterface
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
}
