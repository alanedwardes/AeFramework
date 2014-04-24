<?php
use AeFramework\Views as Views;

class RedirectViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testTemporaryRedirectViewRedirects()
	{
		$redirect = new Views\TemporaryRedirectView('http://www.example.com/');
		
		$redirect->request('GET');
		
		$this->assertSame($redirect->headers, ['Location' => 'http://www.example.com/']);
		$this->assertSame($redirect->code, AeFramework\HttpCode::Found);
	}
	
	public function testPermanentRedirectViewRedirects()
	{
		$redirect = new Views\PermanentRedirectView('http://www.example.com/');
		
		$redirect->request('GET');
		
		$this->assertSame($redirect->headers, ['Location' => 'http://www.example.com/']);
		$this->assertSame($redirect->code, AeFramework\HttpCode::MovedPermanently);
	}
	
	public function testRedirectViewWithMapperParameters()
	{
		$redirect = new Views\TemporaryRedirectView('http://www.example.com/posts/%s/');
		
		# Normally set by the mapper, but we are mocking here
		$redirect->request('GET', ['post_slug' => 'testing']);
		
		$this->assertSame($redirect->headers, ['Location' => 'http://www.example.com/posts/testing/']);
	}
}