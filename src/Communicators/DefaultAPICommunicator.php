<?php

namespace Marks\Stockfighter\Communicators;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\AggregateException;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\RejectionException;
use GuzzleHttp\Psr7\Response;
use Marks\Stockfighter\Contracts\APICommunicatorContract;
use Marks\Stockfighter\Exceptions\StockfighterException;
use Marks\Stockfighter\Exceptions\StockfighterRequestException;
use Marks\Stockfighter\Stockfighter;
use Psr\Http\Message\ResponseInterface;

class DefaultAPICommunicator extends Communicator implements APICommunicatorContract
{
	/**
	 * The stockfighter instance.
	 * @var Stockfighter
	 */
	protected $stockfighter = null;

	/**
	 * The API prefix used when making requests.
	 * @var string
	 */
	protected $api_prefix = '/ob/api/';

	/**
	 * The host for the API.
	 * @var string
	 */
	protected $api_host = 'https://api.stockfighter.io';

	/**
	 * The Guzzle client.
	 * @var Client
	 */
	protected $client = null;

	/**
	 * The global rejection callback when using promises.
	 * @var callable
	 */
	protected $global_rejected = null;

	/**
	 * The global fullfilled callback when using promises.
	 * @var callable
	 */
	protected $global_fulfilled = null;

	public function __construct(Stockfighter $stockfighter)
	{
		$this->stockfighter = $stockfighter;
		$this->client = new Client();
	}

	public function setApiHost($host)
	{
		$this->ensureNoTrailingSlash($host);
		$this->api_host = $host;

		return $this;
	}

	public function setApiPrefix($prefix)
	{
		$prefix = $this->ensureLeadingSlash($prefix);
		$prefix = $this->ensureTrailingSlash($prefix);
		$this->api_prefix = $prefix;

		return $this;
	}

	public function setFulfilled(callable $fulfilled)
	{
		$this->global_fulfilled = $fulfilled;

		return $this;
	}

	public function setRejected(callable $rejected)
	{
		$this->global_rejected = $rejected;

		return $this;
	}

	/**
	 * Given a method, gets the key to use in the options array.
	 *
	 * @param string $method
	 *
	 * @return string
	 * @throws StockfighterException
	 */
	protected function getDataKey($method)
	{
		$keys = [
			'GET' => 'query',
			'POST' => 'json',
		];
		if (array_key_exists(strtoupper($method), $keys)) {
			return $keys[strtoupper($method)];
		} else {
			throw new StockfighterException('Method ' . $method . ' does not support sending data.');
		}
	}

	/**
	 * Gets headers for any request to the API.
	 *
	 * @return array
	 */
	protected function getHeaders()
	{
		return [
			'X-Starfighter-Authorization' => $this->stockfighter->getApiKey(),
		];
	}

	/**
	 * Given a method and any associated data, builds the request options
	 * with the headers and data added to the appropriate option key.
	 *
	 * @param string $method The HTTP method.
	 * @param array  $data   The data to pass with the request.
	 *
	 * @return array
	 */
	protected function getRequestOptions($method, array $data = array())
	{
		$options['headers'] = $this->getHeaders();
		$options['base_uri'] = $this->api_host;
		$options['exceptions'] = false; // We handle failed requests.
		if (!empty($data)) {
			$options[$this->getDataKey($method)] = $data;
		}

		return $options;
	}

	/**
	 * Given a request, decodes and returns the JSON returned from the
	 * API.
	 *
	 * @param ResponseInterface $response
	 *
	 * @return array
	 * @throws StockfighterException
	 */
	protected function getBody(ResponseInterface $response)
	{
		$raw_body = $response->getBody()->getContents();
		if (!$raw_body) {
			throw new StockfighterRequestException($raw_body, $response->getStatusCode(),
				'Response was empty.');
		}

		$body = json_decode($raw_body, true);
		if (!$body) {
			throw new StockfighterRequestException($raw_body, $response->getStatusCode(),
				'Response was malformed.');
		}

		return $body;
	}

	/**
	 * Given a response and optionally the body, handles any possible cases
	 * with an invalid response from the server.
	 *
	 * @param ResponseInterface $response
	 * @param bool              $body
	 *
	 * @throws StockfighterRequestException
	 */
	protected function handleResponseErrors(ResponseInterface $response, $body = false)
	{
		if (!$body) {
			$body = $this->getBody($response);
		}

		if ($response->getStatusCode() != 200) {
			throw new StockfighterRequestException($body, $response->getStatusCode());
		}

		if (!is_array($body) || !array_key_exists('ok', $body)) {
			throw new StockfighterRequestException($body, $response->getStatusCode(), 'Body is invalid.');
		}

		if (!$body['ok']) {
			throw new StockfighterRequestException($body, $response->getStatusCode(), 'Body was not OK.');
		}
	}

	public function request($method, $endpoint, array $data = array())
	{
		$response = $this->client->request($method, $this->api_prefix . $endpoint,
			$this->getRequestOptions($method, $data));

		$body = $this->getBody($response);
		$this->handleResponseErrors($response, $body);

		return $body;
	}

	public function requestAsync($method, $endpoint, array $data = array())
	{
		return $this->client->requestAsync($method, $this->api_prefix . $endpoint,
			$this->getRequestOptions($method, $data))->then(function (ResponseInterface $res) {
			return $this->getBody($res);
		}, function (RequestException $e) {

			// See if we can simplify the exception.
			if (!$e->hasResponse()) {
				throw new StockfighterRequestException('', -1, 'No response from server.');
			}
			$this->handleResponseErrors($e->getResponse());

			// Otherwise, throw a general error.
			throw new StockfighterRequestException('', -1, 'Unknown error: ' . $e->getMessage());

		})->then($this->global_fulfilled, $this->global_rejected);
	}

	public function get($endpoint, array $data = array())
	{
		return $this->request('GET', $endpoint, $data);
	}

	public function getAsync($endpoint, array $data = array())
	{
		return $this->requestAsync('GET', $endpoint, $data);
	}

	public function post($endpoint, array $data = array())
	{
		return $this->request('POST', $endpoint, $data);
	}

	public function postAsync($endpoint, array $data = array())
	{
		return $this->requestAsync('POST', $endpoint, $data);
	}
}
