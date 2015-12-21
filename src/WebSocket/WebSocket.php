<?php

namespace Marks\Stockfighter\WebSocket;

use Marks\Stockfighter\Contracts\WebSocketContract;
use WebSocket\Client;

class WebSocket implements WebSocketContract
{
	/**
	 * The URL to the websocket endpoint.
	 * @var string
	 */
	protected $url = '';

	/**
	 * The websocket client.
	 * @var Client
	 */
	protected $client = null;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function connect()
	{
		$this->client = new Client($this->url);
	}
}
