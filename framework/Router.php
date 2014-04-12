<?php
namespace ae\framework;

class Router
{
	# Properties
	public $mappers = array();
	public $error_views = array();
	public $cache_provider = null;
	public $cache_key = null;
	public $path = '';
	
	# Member Functions	
	public function error($code, View $view)
	{
		$this->error_views[$code] = $view;
	}
	
	public function route(Mapper $mapper)
	{
		$this->mappers[] = $mapper;
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
	
	public function despatch()
	{
		$this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		
		foreach ($this->mappers as $mapper)
			if ($mapper->match($this->path))
				return $this->serveView($mapper->view);
		
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
			$view->path = $this->path;
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