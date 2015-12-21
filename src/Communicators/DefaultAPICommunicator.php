<?php

namespace Marks\Stockfighter\Communicators;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Marks\Stockfighter\Contracts\APICommunicatorContract;
use Marks\Stockfighter\Exceptions\StockfighterException;
use Marks\Stockfighter\Exceptions\StockfighterRequestException;
use Marks\Stockfighter\Stockfighter;

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

	public function __construct(Stockfighter $stockfighter)
	{
		$this->stockfighter = $stockfighter;
		$this->client = new Client();
	}

	public function setApiHost($host)
	{
		$this->ensureNoTrailingSlash($host);

		$this->api_host = $host;
	}

	public function setApiPrefix($prefix)
	{
		$prefix = $this->ensureLeadingSlash($prefix);
		$prefix = $this->ensureTrailingSlash($prefix);

		$this->api_prefix = $prefix;
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
	 * @param Response $response
	 *
	 * @return array
	 * @throws StockfighterException
	 */
	protected function getBody(Response $response)
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

	public function request($method, $endpoint, array $data = array())
	{
		$response = $this->client->request($method, $this->api_prefix . $endpoint,
			$this->getRequestOptions($method, $data));

		$body = $this->getBody($response);

		if ($response->getStatusCode() != 200) {
			throw new StockfighterRequestException($body, $response->getStatusCode());
		}

		if (!$body['ok']) {
			throw new StockfighterRequestException($body, $response->getStatusCode(), 'Body was not OK.');
		}

		return $body;
	}

	public function get($endpoint, array $data = array())
	{
		return $this->request('GET', $endpoint, $data);
	}

	public function post($endpoint, array $data = array())
	{
		return $this->request('POST', $endpoint, $data);
	}
}
