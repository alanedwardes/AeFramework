<?php
class RedirectViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testTemporaryRedirectViewRedirects()
	{
		$redirect = new AeFramework\TemporaryRedirectView('http://www.example.com/');
		
		$this->assertSame($redirect->headers(), ['Location' => 'http://www.example.com/']);
		$this->assertSame($redirect->code(), AeFramework\HttpCode::Found);
	}
	
	public function testPermanentRedirectViewRedirects()
	{
		$redirect = new AeFramework\PermanentRedirectView('http://www.example.com/');
		
		$this->assertSame($redirect->headers(), ['Location' => 'http://www.example.com/']);
		$this->assertSame($redirect->code(), AeFramework\HttpCode::MovedPermanently);
	}
	
	public function testRedirectViewWithMapperParameters()
	{
		$redirect = new AeFramework\TemporaryRedirectView('http://www.example.com/posts/%s/');
		
		# Normally set by the mapper, but we are mocking here
		$redirect->map(array('post_slug' => 'testing'));
		
		$this->assertSame($redirect->headers(), ['Location' => 'http://www.example.com/posts/testing/']);
	}
}