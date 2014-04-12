<?php
namespace AeFramework;

class Router
{
	# Properties
	public $mappers = array();
	public $error_views = array();
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
			throw new \Exception(sprintf("Error %s", $code));
		}
	}
	
	public function serveView(View $view)
	{
		$view->path = $this->path;
		$rendered = $view->render();
		$this->send($rendered, $view->code);
	}
	
	public function send($data, $code = HttpCode::Ok)
	{
		http_response_code($code);
		header(sprintf('X-Generate: %.4fs', microtime(true) - AE_START_LOAD_TIME));
		echo $data;
	}
}