<?php
namespace ae\framework;

class Router
{
	# Constants
	const NotFound = 404;
	const Ok = 200;

	# Properties
	public $main_views = array();
	public $error_views = array();
	public $cache_provider = null;
	public $cache_key = null;
	public $base = '/';
	public $path = '';
	
	# Members Functions	
	public function base($base)
	{
		$this->base = $base;
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
		return crc32($this->path . $this->cache_key . $cacheHash);
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
	
	public function getPath()
	{
		$path = $_SERVER['REQUEST_URI'];
		$path = ltrim($path, '/');
		$path = preg_replace('/^' . preg_quote(trim($this->base, '/')) . '/', '', $path);
		$path = ltrim($path, '/');
		$path = '/' . $path;
		return parse_url($path, PHP_URL_PATH);
	}
	
	public function despatch()
	{
		$this->path = $this->getPath();
		
		foreach ($this->main_views as $view_path => $view)
		{
			if ($view_path === $this->path)
			{
				return $this->serveView($view);
			}
		}
		
		$this->serveError(HttpCode::NotFound);
	}
	
	public function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			$this->error_views[$code]->code = $code;
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
			$this->send($rendered, $view->code);
		}
	}
	
	public function send($data, $code = HttpCode::Ok)
	{
		http_response_code($code);
		header(sprintf('X-Generate: %.4fs', microtime(true) - AE_START_LOAD_TIME));
		echo $data;
	}
}