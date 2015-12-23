<?php

namespace Marks\Stockfighter\WebSocket;

use Marks\Stockfighter\Objects\Quote;

class WebSocketQuote extends WebSocket
{
	protected function handleContents(array $contents)
	{
		return new Quote($contents);
	}
}
