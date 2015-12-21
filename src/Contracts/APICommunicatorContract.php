<?php

namespace Marks\Stockfighter\Contracts;

use Marks\Stockfighter\Stockfighter;

interface APICommunicatorContract
{
	public function __construct(Stockfighter $stockfighter);

	/**
	 * Sets the API prefix (use this when setting the API prefix
	 * to anything but the default).
	 *
	 * @param string $prefix
	 */
	public function setApiPrefix($prefix);

	/**
	 * Sets the API host (use this when setting the API host
	 * to anything but the default).
	 *
	 * @param string $host
	 */
	public function setApiHost($host);

	/**
	 * Makes a request to the server at the specified endpoint. Returns
	 * an array of decoded JSON from the server.
	 *
	 * @param string $method   The HTTP method to use for the request.
	 * @param string $endpoint The endpoint to call (everything after
	 *                         the API prefix).
	 * @param array  $data     Any data to pass with the request.
	 *
	 * @return array
	 */
	public function request($method, $endpoint, array $data = array());

	/**
	 * Makes a GET request to the server.
	 *
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @see request()
	 * @return mixed
	 */
	public function get($endpoint, array $data = array());

	/**
	 * Makes a POST request to the server.
	 *
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @see request()
	 * @return mixed
	 */
	public function post($endpoint, array $data = array());
}
