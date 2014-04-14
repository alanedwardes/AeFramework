<?php
class CacheableView implements AeFramework\IView, AeFramework\ICacheable
{
	public $hash = null;
	public $body_generated;
	
	public function map($params = [])
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
	private $cache = [];
	
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
		$this->cacheable_view = new CacheableView;
	}
	
	public function testCacheableRouterCaches()
	{
		$this->router = new AeFramework\CachedRouter($this->cache);
	
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertFalse($this->cacheable_view->body_generated);
	}
	
	public function testCacheableRouterViewHashInvalidatesCache()
	{
		$this->router = new AeFramework\CachedRouter($this->cache);
	
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->cacheable_view->hash = 'hash';
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
		
		$this->cacheable_view->body_generated = false;
		$this->cacheable_view->hash = 'newhash';
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
	}
	
	public function testCacheableRouterSameCacheKeyWithTwoInstances()
	{
		# Router 1
		$this->router = new AeFramework\CachedRouter($this->cache, 'cachekey');
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
		
		# Router 2
		$this->router = new AeFramework\CachedRouter($this->cache, 'cachekey');
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertFalse($this->cacheable_view->body_generated);
	}
	
	public function testCacheableRouterDifferentCacheKeyWithTwoInstances()
	{
		# Router 1
		$this->router = new AeFramework\CachedRouter($this->cache, 'cachekey');
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
		
		# Router 2
		$this->router = new AeFramework\CachedRouter($this->cache, 'anothercachekey');
		$this->router->route(new AeFramework\StringMapper('/testing', $this->cacheable_view));
		
		$this->cacheable_view->body_generated = false;
		$this->router->despatch('/testing');
		$this->assertTrue($this->cacheable_view->body_generated);
	}
}