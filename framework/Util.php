<?php
namespace ae\framework;

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
}