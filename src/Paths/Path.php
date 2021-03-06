<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Stockfighter;

abstract class Path
{
	/**
	 * A reference to the stockfighter instance.
	 * @var Stockfighter
	 */
	protected $stockfighter = null;

	/**
	 * A reference to the parent path.
	 * @var Path
	 */
	protected $parent = null;

	public function __construct(Stockfighter $stockfighter, Path $parent = null)
	{
		$this->stockfighter = $stockfighter;
		$this->parent = $parent;
	}

	/**
	 * Gets the communicator for the current stockfighter instance.
	 *
	 * @return \Marks\Stockfighter\Contracts\APICommunicatorContract
	 */
	public function communicator()
	{
		return $this->stockfighter->getCommunicator();
	}

	/**
	 * Gets the prefix for the current path.
	 * @return string
	 */
	protected function getPathPrefix()
	{
		return '';
	}

	/**
	 * Gets the path for the endpoint.
	 *
	 * @param string $endpoint The endpoint you're making the request to.
	 *
	 * @return string
	 */
	protected function endpoint($endpoint)
	{
		return $this->getPathPrefix() . $endpoint;
	}
}
