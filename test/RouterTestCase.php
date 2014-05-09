<?php
require_once 'Helpers/TestRouterServedView.php';

use AeFramework\Views as Views;
use AeFramework\Mapping as Mapping;

class TestViewWithTwoConstructorParameters extends AeFramework\Views\TextView
{
	public function __construct($one, $two)
	{
		parent::__construct($one . '_' . $two);
	}
}

class RouterTestCase extends PHPUnit_Framework_TestCase
{
	private $router;
	private $test_view1;
	private $test_view2;
	
	protected function setUp()
	{
		$this->router = new TestRouterServedView;
		$this->test_view1 = new Views\TextView('test_view1');
		$this->test_view2 = new Views\TextView('test_view2');
		
		$this->delegate_router = new TestRouterServedView;
	}
	
	public function testRouterRoute()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', $this->test_view1));
		
		$this->router->despatch('/testing/');
		
		$this->assertSame($this->router->served_view, $this->test_view1);
	}
	
	public function testRouterNotFoundRoute()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', $this->test_view1));
		$this->router->error(\AeFramework\Http\Code::NotFound, $this->test_view2);
		
		$this->router->despatch('/not-found/');
		
		$this->assertSame($this->router->served_view, $this->test_view2);
	}
	
	/**
	 * @expectedException     \AeFramework\Http\CodeException
	 * @expectedExceptionCode 404
	 */
	public function testRouterNotFoundException()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', $this->test_view1));
		
		$this->router->despatch('/not-found/');
	}
	
	public function testRouterDelegateRoutingChildRouterNotFoundRoute()
	{
		$this->router->route(new Mapping\RegexMapper('^/testing', $this->delegate_router));
		
		$this->delegate_router->error(\AeFramework\Http\Code::NotFound, $this->test_view2);
		
		$this->router->despatch('/testing/test');
		
		$this->assertSame($this->delegate_router->served_view, $this->test_view2);
	}
	
	public function testRouterDeferredViewConstruction()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', ['AeFramework\Views\TextView', 'test_deferred']));
		
		$this->router->despatch('/testing/');
		
		$this->assertSame($this->router->served_view->response(), 'test_deferred');
	}
	
	public function testRouterDeferredViewConstructionWithMultipleConstructorParameters()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', [
			'TestViewWithTwoConstructorParameters',
			'test',
			'deferred'
		]));
		
		$this->router->despatch('/testing/');
		
		$this->assertSame($this->router->served_view->response(), 'test_deferred');
	}
	
	public function testRouterDeferredViewConstructionWithConstructorParametersAndClassMembers()
	{
		$this->router->route(new Mapping\StringMapper('/testing/', [
			'AeFramework\Views\TextView',
			'testing',
			'text' => 'test_deferred'
		]));
		
		$this->router->despatch('/testing/');
		
		$this->assertSame($this->router->served_view->response(), 'test_deferred');
	}
	
	public function testRouterDelegateRouting()
	{
		$this->router->route(new Mapping\RegexMapper('^/testing', $this->delegate_router));
		
		$this->delegate_router->route(new Mapping\StringMapper('/test', $this->test_view1));
		
		$this->router->despatch('/testing/test');
		
		$this->assertSame($this->delegate_router->served_view, $this->test_view1);
	}
	
	public function testRouterDelegateRoutingParentRouterStillRoutes()
	{
		$this->router->route(new Mapping\RegexMapper('^/testing', $this->delegate_router));
		$this->router->route(new Mapping\RegexMapper('/view2/', $this->test_view2));
		
		$this->delegate_router->route(new Mapping\StringMapper('/test', $this->test_view1));
		
		$this->router->despatch('/view2/');
		
		$this->assertSame($this->router->served_view, $this->test_view2);
	}
}