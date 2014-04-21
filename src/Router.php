<?php
namespace AeFramework;

class Router
{
	# Properties
	public $mappers = [];
	public $error_views = [];
	public $path = '';
	
	# Member Functions
	public function error($code, $view)
	{
		$this->error_views[$code] = $view;
	}
	
	public function route(Mapper $mapper)
	{
		$this->mappers[] = $mapper;
	}
	
	private function findViewFromMappers()
	{
		foreach ($this->mappers as $mapper)
			if ($mapper->match($this->path))
				return $this->serveView($this->getView($mapper->view, $mapper->params));
		
		throw new NotFoundException();
	}
	
	public function despatch($path = null)
	{
		# If $path is null, use SERVER['REQUEST_URI']
		$this->path = ($path != null ? $path : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		
		try
		{
			return $this->findViewFromMappers($path);
		}
		catch (ErrorCodeException $e)
		{
			return $this->serveError($e->httpCode);
		}
	}
	
	# Protected functions
	protected function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			return $this->serveView($this->getView($this->error_views[$code]), $code);
		}
		else
		{
			throw new ErrorCodeException($code);
		}
	}
	
	protected function getView($viewdata, array $mapper_params = [])
	{
		if ($viewdata instanceof IView)
			$instance = $viewdata;
		elseif (isset($viewdata[1]))
			$instance = ClassFactory::constructClassAndFillMembers($viewdata[0], $viewdata[1]);
		else
			$instance = ClassFactory::constructClass($viewdata[0]);
		
		$instance->map($mapper_params);
		return $instance;
	}
	
	protected function serveView(IView $view)
	{
		http_response_code($this->getCode($view));
		
		foreach ($this->getHeaders($view) as $name => $value)
			header(sprintf('%s: %s', $name, $value));
		
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