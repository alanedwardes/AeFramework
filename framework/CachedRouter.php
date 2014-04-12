<?php
namespace ae\framework;

class CachedRouter extends Router
{
	private $cache_provider = null;
	private $cache_key = '';
	
	public function __construct(Cache $cache_provider, $cache_key = '')
	{
		$this->cache_provider = $cache_provider;
		$this->cache_key = $cache_key;
	}
	
	public function serveView(View $view)
	{
		if ($cache_result = $this->fromCache($view->cacheHash()))
		{
			$this->send($cache_result);
		}
		else
		{
			$view->path = $this->path;
			$rendered = $view->render();
			$this->toCache($rendered, $view->cacheHash());
			$this->send($rendered, $view->code);
		}
	}
	
	public function toCache($data, $cacheHash)
	{
		if ($this->cache_provider === null)
			return false;
			
		if ($this->cache_key === null)
			return false;
		
		return $this->cache_provider->set($this->cacheKey($cacheHash), $data);
	}
	
	public function cacheKey($cacheHash)
	{
		return Util::checksum($this->path, $this->cache_key, $cacheHash);
	}
	
	public function fromCache($cacheHash)
	{
		if ($this->cache_provider === null)
			return false;
			
		if ($this->cache_key === null)
			return false;
		
		if ($get = $this->cache_provider->get($this->cacheKey($cacheHash)))
		{
			header('X-Cache: Hit');
			return $get;
		}
		else
		{
			header('X-Cache: Miss');
			return false;
		}
	}
}