<?php
namespace AeFramework;

class Router
{
	# Properties
	public $mappers = [];
	public $error_views = [];
	public $path = '';
	
	# Member Functions
	public function error($code, IView $view)
	{
		$this->error_views[$code] = $view;
	}
	
	public function route(Mapper $mapper)
	{
		$this->mappers[] = $mapper;
	}
	
	public function despatch($path = null)
	{
		# If $path is null, use SERVER['REQUEST_URI']
		$this->path = ($path != null ? $path : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		
		foreach ($this->mappers as $mapper)
			if ($mapper->match($this->path))
				return $this->serveView($mapper->view);
		
		return $this->serveError(HttpCode::NotFound);
	}
	
	# Protected functions
	protected function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			return $this->serveView($this->error_views[$code], $code);
		}
		else
		{
			throw new \Exception(sprintf("Error %s", $code));
		}
	}
	
	protected function serveView(IView $view)
	{
		http_response_code($this->getCode($view));
		
		foreach ($this->getHeaders($view) as $header)
			header($header);
		
		return $this->getBody($view);
	}
	
	protected function getCode(IView $view)
	{
		return $view->code();
	}
	
	protected function getHeaders(IView $view)
	{
		return $view->headers();
	}
	
	protected function getBody(IView $view)
	{
		return $view->body();
	}
}