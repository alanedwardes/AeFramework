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
		return func_get_args()[array_rand(func_get_args())];
	}
	
	public static function camelCaseToSpaces($string)
	{
		return trim(preg_replace('/(?<=\\w)(?=[A-Z])/',' $1', $string));
	}
	
	public static function formatPathExpression($expression)
	{
		return sprintf('/%s/', str_replace('/', '\/', $expression));
	}
}