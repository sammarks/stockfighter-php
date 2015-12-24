<?php

namespace Marks\Stockfighter\WebSocket;

use Marks\Stockfighter\Objects\Execution;

class WebSocketExecution extends WebSocket
{
	protected function handleContents(array $contents)
	{
		return new Execution($contents);
	}
}
