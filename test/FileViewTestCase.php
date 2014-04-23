<?php
class FileViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testInlineFileView()
	{
		# Create a download test with this source file
		$file = new \AeFramework\Views\InlineFileView(__FILE__, 'application/x-httpd-php');
		
		$this->assertSame($file->headers()['Content-Type'] ,'application/x-httpd-php');
	}
	
	public function testDownloadFileView()
	{
		# Create a download test with this source file
		$file = new \AeFramework\Views\DownloadFileView(__FILE__, 'application/x-httpd-php', 'FileViewTestCase.php');
		
		$this->assertSame($file->headers()['Content-Disposition'], 'attachment; filename="FileViewTestCase.php"');
		$this->assertSame($file->headers()['Content-Type'], 'application/x-httpd-php');
	}
}