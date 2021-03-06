<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * CodeIgniter Array Helpers
 */

if (! function_exists('dot_array_search'))
{
	/**
	 * Searches an array through dot syntax. Supports
	 * wildcard searches, like foo.*.bar
	 *
	 * @param string $index
	 * @param array  $array
	 *
	 * @return mixed|null
	 */
	function dot_array_search(string $index, array $array)
	{
		$segments = explode('.', rtrim(rtrim($index, '* '), '.'));

		return _array_search_dot($segments, $array);
	}
}

if (! function_exists('_array_search_dot'))
{
	/**
	 * Used by dot_array_search to recursively search the
	 * array with wildcards.
	 *
	 * @param array $indexes
	 * @param array $array
	 *
	 * @return mixed|null
	 */
	function _array_search_dot(array $indexes, array $array)
	{
		// Grab the current index
		$currentIndex = $indexes
			? array_shift($indexes)
			: null;

		if ((empty($currentIndex)  && intval($currentIndex) !== 0) || (! isset($array[$currentIndex]) && $currentIndex !== '*'))
		{
			return null;
		}

		// Handle Wildcard (*)
		if ($currentIndex === '*')
		{
			// If $array has more than 1 item, we have to loop over each.
			foreach ($array as $value)
			{
				$answer = _array_search_dot($indexes, $value);

				if ($answer !== null)
				{
					return $answer;
				}
			}

			// Still here after searching all child nodes?
			return null;
		}

		// If this is the last index, make sure to return it now,
		// and not try to recurse through things.
		if (empty($indexes))
		{
			return $array[$currentIndex];
		}

		// Do we need to recursively search this value?
		if (is_array($array[$currentIndex]) && $array[$currentIndex])
		{
			return _array_search_dot($indexes, $array[$currentIndex]);
		}

		// Otherwise we've found our match!
		return $array[$currentIndex];
	}
}

if (! function_exists('array_deep_search'))
{
	/**
	 * Returns the value of an element at a key in an array of uncertain depth.
	 *
	 * @param mixed $key
	 * @param array $array
	 *
	 * @return mixed|null
	 */
	function array_deep_search($key, array $array)
	{
		if (isset($array[$key]))
		{
			return $array[$key];
		}

		foreach ($array as $value)
		{
			if (is_array($value))
			{
				if ($result = array_deep_search($key, $value))
				{
					return $result;
				}
			}
		}

		return null;
	}
}
