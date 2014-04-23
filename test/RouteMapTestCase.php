<?php
require_once 'Helpers/TestRouterServedView.php';

use AeFramework\Routing as Routing;
use AeFramework\Mapping as Mapping;
use AeFramework\Views as Views;

class RouteMapTestCase extends PHPUnit_Framework_TestCase
{
	private $router;
	
	protected function setUp()
	{
		$this->router = new TestRouterServedView;
	}

	public function testMap()
	{
		Routing\RouteMap::map($this->router, [
			[new Mapping\StringMapper('/testing/', new Views\TextView('test_view'))]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testMapDeferredView()
	{
		Routing\RouteMap::map($this->router, [
			[new Mapping\StringMapper('/testing/', ['AeFramework\Views\TextView', 'test_view'])]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testStringMapperFromString()
	{
		Routing\RouteMap::map($this->router, [
			['/testing/', 'AeFramework\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
	
	public function testRegexMapperFromString()
	{
		Routing\RouteMap::map($this->router, [
			['r^/testing/$', 'AeFramework\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->body());
	}
}