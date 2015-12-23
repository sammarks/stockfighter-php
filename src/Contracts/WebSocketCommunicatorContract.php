<?php

namespace Marks\Stockfighter\Contracts;

interface WebSocketCommunicatorContract
{
	/**
	 * Sets the websockets prefix (use this when setting the websockets prefix
	 * to anything but the default).
	 *
	 * @param string $prefix
	 */
	public function setWebSocketsPrefix($prefix);

	/**
	 * Sets the websockets host (use this when setting the websockets host
	 * to anything but the default).
	 *
	 * @param string $host
	 */
	public function setWebSocketsHost($host);

	/**
	 * Gets a Web Socket contract for receiving quotes.
	 *
	 * @param string      $account The account name.
	 * @param string      $venue   The name of the venue.
	 * @param string|bool $stock   Either the symbol of the stock or false to
	 *                             not filter by stock.
	 *
	 * @return WebSocketContract
	 */
	public function quotes($account, $venue, $stock = false);
}
