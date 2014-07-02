<?php
namespace Carbo\Extensions\Admin;

class Utilities
{
	public static function formatObjectName($name, array $similar_names = [])
	{
		// Slam to lower
		$name = strtolower($name);
		
		// Change "object_id" columns to just "object"
		if (substr($name, -3) === '_id')
			$name = substr($name, 0, -3);
		
		// Change "is_live" columns to just "live"
		if (substr($name, 0, 3) === 'is_')
			$name = substr($name, 3);
		
		// Find common prefixes to table names
		$common = self::findCommonStrings($similar_names);
		
		// Remove the common prefixes, replace underscore and period with a space
		$name = str_replace([$common, '_', '.'], ['', ' ', ' '], $name);
		
		// Return title case
		return ucwords($name);
	}
	
	public static function findCommonStrings(array $items)
	{
		$prefix = array_shift($items);
		$length = strlen($prefix);
		foreach ($items as $item)
		{
			while ($length && substr($item, 0, $length) !== $prefix)
			{
				$length--;
				$prefix = substr($prefix, 0, -1);
			}
			
			if (!$length)
			{
				break;
			}
		}
		
		return $prefix;
	}
}