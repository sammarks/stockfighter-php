<?php

namespace Marks\Stockfighter\WebSocket;

use Marks\Stockfighter\Contracts\WebSocketContract;
use Marks\Stockfighter\Stockfighter;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class WebSocket implements WebSocketContract
{
	/**
	 * The URL to the websocket endpoint.
	 * @var string
	 */
	protected $url = '';

	/**
	 * The websocket client.
	 * @var \Devristo\Phpws\Client\WebSocket
	 */
	protected $client = null;

	/**
	 * The Stockfighter instance.
	 * @var Stockfighter
	 */
	protected $stockfighter = null;

	public function __construct($url, Stockfighter $stockfighter)
	{
		$this->url = $url;
		$this->stockfighter = $stockfighter;

		// Create the client (and a logger because the library requires it).
		$logger = new Logger();
		$writer = new Stream('php://output');
		$logger->addWriter($writer);
		$this->client = new \Devristo\Phpws\Client\WebSocket($this->url, $this->stockfighter->loop, $logger);
	}

	public function connect()
	{
		$this->client->open();
	}

	public function receive(callable $callback)
	{
		$this->client->on('message', function ($message) use ($callback) {

			// Get the contents.
			$contents = json_decode($message, true);
			if (!$contents) return;

			// Handle the contents.
			$value = $this->handleContents($contents);

			// Call the callback with the value.
			$callback($value);

		});
	}

	/**
	 * Given contents returned from the websocket API, does any conversions
	 * and returns the final result.
	 *
	 * @param array $contents
	 *
	 * @return array
	 */
	protected function handleContents(array $contents)
	{
		return $contents;
	}
}
