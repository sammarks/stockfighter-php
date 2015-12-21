<?php

namespace Marks\Stockfighter\Communicators;

class Communicator
{
	/**
	 * Given a value, makes sure it doesn't have a trailing slash.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	protected function ensureNoTrailingSlash($value)
	{
		if (strpos($value, '/', strlen($value) - 1) !== false) {
			$value = substr($value, 0, strlen($value) - 1);
		}

		return $value;
	}

	/**
	 * Given a value, makes sure it starts with a leading slash.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	protected function ensureLeadingSlash($value)
	{
		if (strpos($value, '/') !== 0) {
			$value = '/' . $value;
		}

		return $value;
	}

	/**
	 * Given a value, makes sure it ends with a trailing slash.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	protected function ensureTrailingSlash($value)
	{
		if (strpos($value, '/', strlen($value) - 1) === false) {
			$value .= '/';
		}

		return $value;
	}
}
