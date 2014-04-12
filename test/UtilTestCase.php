<?php
use AeFramework\Util as Util;

class UtilTestCase extends PHPUnit_Framework_TestCase
{
	public function testUtilJoinPath()
	{
		$path1 = 'one' . DIRECTORY_SEPARATOR . 'two' . DIRECTORY_SEPARATOR . 'three';
		$path2 = Util::joinPath('one', 'two', 'three');
	
		$this->assertSame($path1, $path2);
	}
	
	public function testUtilChecksum()
	{
		$checksum1 = Util::checksum('testing');
		$checksum2 = sprintf('%u', crc32('testing'));
		
		$this->assertSame($checksum1, $checksum2);
	}
	
	public function testUtilMultipleChecksum()
	{
		$checksum1 = Util::checksum('one', 'two', 'three');
		$checksum2 = sprintf('%u', crc32('one' . 'two' . 'three'));
		
		$this->assertSame($checksum1, $checksum2);
	}
}