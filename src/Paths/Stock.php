<?php

namespace Marks\Stockfighter\Paths;

class Stock extends Venue
{
	protected $resource_name = 'stocks';

	public function info()
	{
		$response = $this->communicator()->get($this->endpoint(''));
	}
}
