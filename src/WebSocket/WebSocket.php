<?php

namespace Marks\Stockfighter\WebSocket;

use Devristo\Phpws\Messaging\WebSocketMessageInterface;
use Marks\Stockfighter\Contracts\WebSocketContract;
use Marks\Stockfighter\Stockfighter;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
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

	/**
	 * The Zend logger.
	 * @var LoggerInterface
	 */
	protected $logger = null;

	public function __construct($url, Stockfighter $stockfighter)
	{
		$this->url = $url;
		$this->stockfighter = $stockfighter;

		// Create the client (and a logger because the library requires it).
		// Well, we might as well embrace the logger since we have to use it...
		$this->logger = new Logger();
		$writer = new Stream('php://output');
		$this->logger->addWriter($writer);
		$this->client = new \Devristo\Phpws\Client\WebSocket($this->url, $this->stockfighter->loop,
			$this->logger);
	}

	public function connect()
	{
		$this->client->open();
	}

	public function receive(callable $callback)
	{
		$this->client->on('request', function () {
			$this->logger->notice('Request object created!');
		});

		$this->client->on('handshake', function () {
			$this->logger->notice('Handshake received!');
		});

		$this->client->on('connect', function () {
			$this->logger->notice('Connected!');
		});

		$this->client->on('message', function (WebSocketMessageInterface $message) use ($callback) {

			// Wait for the message to be finalized.
			if (!$message->isFinalised()) {
				return;
			}

			// Get the contents.
			$contents = json_decode($message->getData(), true);
			if (!$contents) return;

			// Handle the contents.
			$value = $this->handleContents($contents);

			// Call the callback with the value.
			$result = $callback($value);

			// If the callback returns true, close the connection.
			if ($result === true) {
				$this->client->close();
			}

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
