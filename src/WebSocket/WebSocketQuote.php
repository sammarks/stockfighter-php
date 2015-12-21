<?php

namespace Marks\Stockfighter\WebSocket;

use Marks\Stockfighter\Contracts\WebSocketQuoteContract;
use Marks\Stockfighter\Objects\Quote;

class WebSocketQuote extends WebSocket implements WebSocketQuoteContract
{
	public function receive()
	{
		$quote = false;
		while ($quote === false) {

			// Get the raw contents.
			$json = $this->client->receive();
			$contents = json_decode($json, true);
			if (!$contents) continue;

			// If the contents are not okay...
			if (!array_key_exists('ok', $contents) || !$contents['ok']) continue;

			// See if we have a quote.
			if (array_key_exists('quote', $contents)) {
				$quote = new Quote($contents['quote']);
			}

		}

		return $quote;
	}
}
