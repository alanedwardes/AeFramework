<?php
require_once 'Helpers/TestRouterServedView.php';

use Carbo\Routing as Routing;
use Carbo\Mapping as Mapping;
use Carbo\Views as Views;

class MapTestCase extends PHPUnit_Framework_TestCase
{
	private $router;
	
	protected function setUp()
	{
		$this->router = new TestRouterServedView;
	}

	public function testMap()
	{
		Mapping\Map::create($this->router, [
			[new Mapping\StringMapper('/testing/', new Views\TextView('test_view'))]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testDeferredMapperForObject()
	{
		Mapping\Map::create($this->router, [
			['/testing/', new Views\TextView('test_view')]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testMapDeferredView()
	{
		Mapping\Map::create($this->router, [
			[new Mapping\StringMapper('/testing/', ['Carbo\Views\TextView', 'test_view'])]
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testStringMapperFromString()
	{
		Mapping\Map::create($this->router, [
			['/testing/', 'Carbo\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
	
	public function testRegexMapperFromString()
	{
		Mapping\Map::create($this->router, [
			['r^/testing/$', 'Carbo\Views\TextView', 'test_view']
		]);
		
		$this->router->despatch('/testing/');
		
		$this->assertSame('test_view', $this->router->served_view->response());
	}
}