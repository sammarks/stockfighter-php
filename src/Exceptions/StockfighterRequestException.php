<?php

namespace Marks\Stockfighter\Exceptions;

class StockfighterRequestException extends StockfighterException
{
	/**
	 * The response body.
	 * @var array|string
	 */
	public $body;

	/**
	 * The response status code.
	 * @var int
	 */
	public $status_code;

	/**
	 * StockfighterRequestException constructor.
	 *
	 * @param array|string $body
	 * @param int          $status_code
	 * @param string|bool  $message
	 */
	public function __construct($body, $status_code, $message = false)
	{
		if (is_array($body) && array_key_exists('error', $body) && !$message) {
			$this->message = $body['error'];
		} else {
			$this->message = $message;
		}
		$this->status_code = $status_code;
		$this->body = $body;
	}
}
