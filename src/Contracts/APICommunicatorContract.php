<?php

namespace Marks\Stockfighter\Contracts;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use Marks\Stockfighter\Stockfighter;

interface APICommunicatorContract
{
	public function __construct(Stockfighter $stockfighter);

	/**
	 * Sets the API prefix (use this when setting the API prefix
	 * to anything but the default).
	 *
	 * @param string $prefix
	 *
	 * @return $this
	 */
	public function setApiPrefix($prefix);

	/**
	 * Sets the API host (use this when setting the API host
	 * to anything but the default).
	 *
	 * @param string $host
	 *
	 * @return $this
	 */
	public function setApiHost($host);

	/**
	 * Sets the global fulfilled callback (when using promises).
	 *
	 * @param callable $fulfilled
	 *
	 * @return $this
	 */
	public function setFulfilled(callable $fulfilled);

	/**
	 * Sets the global rejected callback (when using promises).
	 *
	 * @param callable $rejected
	 *
	 * @return $this
	 */
	public function setRejected(callable $rejected);

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
	 * Makes a request to the server (asynchronously) at the specified endpoint.
	 * Returns a promise object.
	 *
	 * @param string $method
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @see request()
	 * @return PromiseInterface
	 */
	public function requestAsync($method, $endpoint, array $data = array());

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
	 * Makes a GET request (asynchronously) to the server.
	 *
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @see get()
	 * @return PromiseInterface
	 */
	public function getAsync($endpoint, array $data = array());

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

	/**
	 * Makes a POST request to the server (asynchronously).
	 *
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @return PromiseInterface
	 */
	public function postAsync($endpoint, array $data = array());
}
