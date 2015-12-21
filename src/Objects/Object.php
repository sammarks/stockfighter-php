<?php

namespace Marks\Stockfighter\Objects;

class Object
{
	public function __construct(array $object = array())
	{
		if (!empty($object)) {
			foreach ($object as $key => $value) {
				if (!property_exists($this, $key)) continue;
				$this->$key = $value;
			}
		}
	}
}
