<?php
namespace ae\framework;

class Router
{
	# Constants
	const NotFound = 404;

	# Properties
	public $main_views = array();
	public $error_views = array();
	public $cache_provider = null;
	public $cache_key = null;
	
	# Members Functions
	public function getPath()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}
	
	public function error($code, View $view)
	{
		$view->router = $this;
		$this->error_views[$code] = $view;
	}
	
	public function route($path, $view)
	{
		$view->router = $this;
		$this->main_views[$path] = $view;
	}
	
	public function cacheProvider(Cache $provider, $cache_key)
	{
		$this->cache_provider = $provider;
		$this->cache_key = $cache_key;
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
		return crc32($this->getPath() . $this->cache_key . $cacheHash);
	}
	
	public function fromCache($cacheHash)
	{
		if ($this->cache_provider === null)
			return false;
			
		if ($this->cache_key === null)
			return false;
		
		if ($get = $get = $this->cache_provider->get($this->cacheKey($cacheHash)))
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
	
	public function despatch()
	{
		$path = $this->getPath();
		
		foreach ($this->main_views as $view_path => $view)
		{
			if ($view_path === $path)
			{
				return $this->serveView($view);
			}
		}
		
		$this->serveCode(self::NotFound);
	}
	
	public function serveCode($code)
	{
		http_response_code($code);
		
		if (isset($this->error_views[$code]))
		{
			$this->serveView($this->error_views[$code]);
		}
		else
		{
			echo sprintf("Error %s", $code);
		}
	}
	
	public function serveView(View $view)
	{
		if ($cache_result = $this->fromCache($view->cacheHash()))
		{
			$this->send($cache_result);
		}
		else
		{
			$rendered = $view->render();
			$this->toCache($rendered, $view->cacheHash());
			$this->send($rendered);
		}
	}
	
	public function send($data)
	{
		header(sprintf('X-Generate: %.4fs', microtime(true) - AE_START_LOAD_TIME));
		echo $data;
	}
}