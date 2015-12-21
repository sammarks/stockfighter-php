<?php

namespace Marks\Stockfighter\Communicators;

use Marks\Stockfighter\Contracts\WebSocketCommunicatorContract;
use Marks\Stockfighter\Stockfighter;
use Marks\Stockfighter\WebSocket\WebSocketQuote;

class DefaultWebSocketCommunicator extends Communicator implements WebSocketCommunicatorContract
{
	/**
	 * The stockfighter instance.
	 * @var Stockfighter
	 */
	protected $stockfighter = null;

	/**
	 * The host for the websockets API.
	 * @var string
	 */
	protected $websocket_host = 'wss://api.stockfighter.io';

	/**
	 * The prefix used when making websockets requests.
	 * @var string
	 */
	protected $websocket_prefix = '/ob/api/ws/';

	public function __construct(Stockfighter $stockfighter)
	{
		$this->stockfighter = $stockfighter;
	}

	public function setWebSocketsPrefix($prefix)
	{
		$prefix = $this->ensureLeadingSlash($prefix);
		$prefix = $this->ensureTrailingSlash($prefix);

		$this->websocket_prefix = $prefix;
	}

	public function setWebSocketsHost($host)
	{
		$host = $this->ensureNoTrailingSlash($host);

		$this->websocket_host = $host;
	}

	/**
	 * Builds a websocket URL given an account, venue and stock.
	 *
	 * @param string      $account
	 * @param string      $venue
	 * @param string|bool $stock
	 *
	 * @return string
	 */
	protected function buildWebSocketURL($account, $venue, $stock = false)
	{
		$url = $this->websocket_host . $this->websocket_prefix . $account;
		$url .= '/venues/' . $venue . '/tickertape';

		if ($stock) {
			$url .= '/stocks/' . $stock;
		}

		return $url;
	}

	public function quotes($account, $venue, $stock = false)
	{
		$url = $this->buildWebSocketURL($account, $venue, $stock);
		return new WebSocketQuote($url);
	}
}
