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

	/**
	 * Given an array of children and the name of their class,
	 * creates the objects containing those children.
	 *
	 * @param array  $children
	 * @param string $child_class
	 *
	 * @return array
	 */
	protected function getChildren(array $children, $child_class)
	{
		$result = array();
		foreach ($children as $child) {
			$result[] = new $child_class($child);
		}

		return $result;
	}
}
