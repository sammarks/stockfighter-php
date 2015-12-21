<?php

namespace Marks\Stockfighter\Contracts;
use Marks\Stockfighter\Objects\Quote;

interface WebSocketQuoteContract extends WebSocketContract
{
	/**
	 * Gets a the next quote received from the server.
	 *
	 * @return Quote
	 */
	public function receive();
}
