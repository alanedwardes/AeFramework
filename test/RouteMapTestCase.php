<?php
require_once 'Helpers/TestRouterServedView.php';

use Carbo\Routing as Routing;
use Carbo\Mapping as Mapping;
use Carbo\Views as Views;

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
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testDeferredMapperForObject()
	{
		Routing\RouteMap::map($this->router, [
			['/testing/', new Views\TextView('test_view')]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testMapDeferredView()
	{
		Routing\RouteMap::map($this->router, [
			[new Mapping\StringMapper('/testing/', ['Carbo\Views\TextView', 'test_view'])]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testStringMapperFromString()
	{
		Routing\RouteMap::map($this->router, [
			['/testing/', 'Carbo\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testRegexMapperFromString()
	{
		Routing\RouteMap::map($this->router, [
			['r^/testing/$', 'Carbo\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
}