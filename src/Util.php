<?php
namespace AeFramework;

class Util
{
	public static function joinPath()
	{
		return join(DIRECTORY_SEPARATOR, func_get_args());
	}
	
	public static function checksum()
	{
		return sprintf('%u', crc32(implode(func_get_args())));
	}
	
	public static function random()
	{
		$items = func_get_args();
		return $items[array_rand($items)];
	}
	
	public static function camelCaseToSpaces($string)
	{
		return trim(preg_replace('/(?<=\\w)(?=[A-Z])/',' $1', $string));
	}
}