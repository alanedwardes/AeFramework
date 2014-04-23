<?php
namespace AeFramework\Routing;

class CachedRouter extends Router
{
	private $cache_provider = null;
	private $cache_key = '';
	
	public function __construct(\AeFramework\Caching\Cache $cache_provider, $cache_key = '')
	{
		$this->cache_provider = $cache_provider;
		$this->cache_key = $cache_key;
	}
	
	protected function getBody(\AeFramework\Views\IView $view)
	{
		if ($view instanceof \AeFramework\Views\ICacheable)
		{
			if ($cached_body = $this->fromCache($view->hash()))
			{
				if (!headers_sent())
					header('X-Cache: hit');
				
				return $cached_body;
			}
			else
			{
				if (!headers_sent())
					header('X-Cache: miss');
				
				$fresh_body = parent::getBody($view);
				$this->toCache($fresh_body, $view->hash(), $view->expire());
				return $fresh_body;
			}
		}
		
		return parent::getBody($view);
	}
	
	protected function toCache($data, $hash, $expire)
	{
		if ($this->cache_provider === null)
			return false;
		
		return $this->cache_provider->set($this->cacheKey($hash), $data, $expire);
	}
	
	protected function cacheKey($hash)
	{
		return \AeFramework\Util::checksum($this->path, $this->cache_key, $hash);
	}
	
	protected function fromCache($hash)
	{
		if ($this->cache_provider === null)
			return false;
		
		return $this->cache_provider->get($this->cacheKey($hash));
	}
}