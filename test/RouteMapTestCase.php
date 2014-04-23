<?php
require_once 'Helpers/TestRouterServedView.php';

class RouteMapTestCase extends PHPUnit_Framework_TestCase
{
	private $router;
	
	protected function setUp()
	{
		$this->router = new TestRouterServedView;
	}

	public function testMap()
	{
		AeFramework\RouteMap::map($this->router, [
			[new AeFramework\StringMapper('/testing/', new AeFramework\TextView('test_view'))]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testMapDeferredView()
	{
		AeFramework\RouteMap::map($this->router, [
			[new AeFramework\StringMapper('/testing/', ['AeFramework\TextView', 'test_view'])]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testStringMapperFromString()
	{
		AeFramework\RouteMap::map($this->router, [
			['/testing/', 'AeFramework\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testRegexMapperFromString()
	{
		AeFramework\RouteMap::map($this->router, [
			['r^/testing/$', 'AeFramework\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
}