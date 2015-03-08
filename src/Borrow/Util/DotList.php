<?php
/**
 * @version  1.0
 */

class DotList
{
	public static function set(array $data, $path, $values = null)
	{
		$_list =& $data;
		$path  = explode('.', $path);
		$count = count($path);
		$last  = $count - 1;

		foreach ($path as $i => $key)
		{
			if (is_numeric($key) && intval($key) > 0 || $key === '0')
			{
				$key = intval($key);
			}

			if ($i === $last)
			{
				$_list[$key] = $values;
				return $data;
			}

			if ( ! isset($_list[$key]))
			{
				$_list[$key] = array();
			}

			$_list =& $_list[$key];
			if ( ! is_array($_list))
			{
				$_list = array();
			}
		}
	}

	public static function get(array $data, $path)
	{
		if (empty($data))
		{
			return null;
		}

		if ($path === null)
		{
			return $data;
		}

		$parts = explode('.', $path);

		while (($key = array_shift($parts)) !== null)
		{
			if (is_array($data) && isset($data[$key]))
			{
				$data =& $data[$key];
			}
			else
			{
				return null;
			}

		}

		return $data;
	}

	public static function remove(array $data, $path)
	{
		$_list =& $data;
		$path  = explode('.', $path);
		$count = count($path);
		$last  = $count - 1;

		foreach ($path as $i => $key)
		{
			if (is_numeric($key) && intval($key) > 0 || $key === '0')
			{
				$key = intval($key);
			}

			if ($i === $last)
			{
				unset($_list[$key]);
				return $data;
			}

			if (!isset($_list[$key]))
			{
				return $data;
			}

			$_list =& $_list[$key];
		}
	}
}