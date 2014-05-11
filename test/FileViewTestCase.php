<?php
class FileViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testInlineFileView()
	{
		# Create a download test with this source file
		$file = new \Carbo\Views\FileView(__FILE__, 'application/x-httpd-php');
		
		$file->request('GET');
		
		$this->assertSame($file->headers['Content-Type'], 'application/x-httpd-php');
	}
	
	public function testDownloadFileView()
	{
		# Create a download test with this source file
		$file = new \Carbo\Views\DownloadFileView(__FILE__, 'application/x-httpd-php', 'FileViewTestCase.php');
		
		$file->request('GET');
		
		$this->assertSame($file->headers['Content-Disposition'], 'attachment; filename="FileViewTestCase.php"');
		$this->assertSame($file->headers['Content-Type'], 'application/x-httpd-php');
	}
}