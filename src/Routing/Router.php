<?php
namespace AeFramework\Routing;

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
	
	public function route(\AeFramework\Mapping\Mapper $mapper)
	{
		$this->mappers[] = $mapper;
	}
	
	private function findViewFromMappers()
	{
		foreach ($this->mappers as $mapper)
		{
			if ($mapper->match($this->path))
			{
				return $this->serveFromMapper($mapper);
			}
		}
		
		throw new \AeFramework\HttpCodeException(\AeFramework\HttpCode::NotFound);
	}
	
	protected function serveFromMapper($mapper)
	{
		$target = $this->constructMapperTarget($mapper->target);
	
		if ($target instanceof Router)
		{
			return $target->despatch($mapper->remaining);
		}
		elseif ($target instanceof \AeFramework\Views\View)
		{
			return $this->serveView($target, $mapper->params);
		}
	}
	
	public function despatch($path = null)
	{
		# If $path is null, use SERVER['REQUEST_URI']
		$this->path = ($path !== null ? $path : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		
		try
		{
			return $this->findViewFromMappers($path);
		}
		catch (\AeFramework\HttpCodeException $e)
		{
			return $this->serveError($e->getCode());
		}
	}
	
	# Protected functions
	protected function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			$view = $this->constructMapperTarget($this->error_views[$code]);
			$view->code = $code;
			return $this->serveView($view);
		}
		else
		{
			throw new \AeFramework\HttpCodeException($code);
		}
	}
	
	protected function constructMapperTarget($target)
	{
		# This could be an already constructed View or Router
		if (!is_array($target))
		{
			return $target;
		}
		elseif (isset($target[1]))
		{
			return \AeFramework\ClassFactory::constructClassAndFillMembers($target[0], [$target[1]]);
		}
		else
		{
			return \AeFramework\ClassFactory::constructClass($target[0]);
		}
	}
	
	protected function serveView(\AeFramework\Views\View $view, array $mapper_params = [])
	{
		$view->request(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null, $mapper_params);
	
		http_response_code($this->getCode($view));
		
		if (!headers_sent())
			foreach ($this->getHeaders($view) as $name => $value)
				header(sprintf('%s: %s', $name, $value));
		
		return $this->getResponse($view);
	}
	
	protected function getCode(\AeFramework\Views\View $view)
	{
		return $view->code;
	}
	
	protected function getHeaders(\AeFramework\Views\View $view)
	{
		return $view->headers;
	}
	
	protected function getResponse(\AeFramework\Views\View $view)
	{
		return $view->response();
	}
}