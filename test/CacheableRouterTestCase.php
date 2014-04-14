<?php
class CacheableView implements AeFramework\IView, AeFramework\ICacheable
{
	public $hash = 'hash';
	public $body_generated;
	
	public function map($params = array())
	{
	}
	
	public function code()
	{
		return AeFramework\HttpCode::NotImplemented;
	}
	
	public function headers()
	{
		return array();
	}
	
	public function body()
	{
		$this->body_generated = true;
		return 'Hello World!';
	}
	
	public function hash()
	{
		return $this->hash;
	}
}

class TemporaryMemoryCache extends AeFramework\Cache
{
	private $cache = array();
	
	public function get($key)
	{
		return @$this->cache[$key];
	}
	
	public function set($key, $value)
	{
		$this->cache[$key] = $value;
	}
}

class CacheableRouterTestCase extends PHPUnit_Framework_TestCase
{
	private $router;
	private $cache;
	private $cacheable_view;
	
	protected function setUp()
	{
		$this->cache = new TemporaryMemoryCache;
		$this->router = new AeFramework\CachedRouter($this->cache);
		$this->cacheable_view = new CacheableView;
	}
	
	public function testCacheableRouterCaches()
	{
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertFalse($this->cacheable_view->body_generated);
	}
}