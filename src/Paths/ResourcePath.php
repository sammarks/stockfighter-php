<?php

namespace Marks\Stockfighter\Paths;

use Marks\Stockfighter\Stockfighter;

class ResourcePath extends Path
{
	protected $resource_id = '';
	protected $resource_name = '';

	/**
	 * ResourcePath constructor.
	 *
	 * @param Stockfighter $stockfighter
	 * @param string       $resource_id
	 * @param Path|null    $parent
	 */
	public function __construct(Stockfighter $stockfighter, $resource_id, Path $parent = null)
	{
		parent::__construct($stockfighter, $parent);
		$this->resource_id = $resource_id;
	}

	protected function getPathPrefix()
	{
		$prefix = $this->parent->getPathPrefix();
		$prefix .= $this->resource_name . '/' . $this->resource_id . '/';

		return $prefix;
	}
}
