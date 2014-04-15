<?php
class RedirectViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testTemporaryRedirectViewRedirects()
	{
		$redirect = new AeFramework\TemporaryRedirectView('http://www.example.com/');
		
		$this->assertSame($redirect->headers(), ['Location' => 'http://www.example.com/']);
	}
}