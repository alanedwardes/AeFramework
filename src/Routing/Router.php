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
		
		throw new \AeFramework\NotFoundException();
	}
	
	protected function serveFromMapper($mapper)
	{
		$target = $this->constructMapperTarget($mapper->target);
	
		if ($target instanceof Router)
		{
			return $target->despatch($mapper->remaining);
		}
		elseif ($target instanceof \AeFramework\Views\IView)
		{
			$target->map($mapper->params);
			return $this->serveView($target);
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
		catch (\AeFramework\ErrorCodeException $e)
		{
			return $this->serveError($e->httpCode);
		}
	}
	
	# Protected functions
	protected function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			return $this->serveView($this->constructMapperTarget($this->error_views[$code]), $code);
		}
		else
		{
			throw new \AeFramework\ErrorCodeException($code);
		}
	}
	
	protected function constructMapperTarget($target)
	{
		# This could be an already constructed IView or Router
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
	
	protected function serveView(\AeFramework\Views\IView $view)
	{
		http_response_code($this->getCode($view));
		
		foreach ($this->getHeaders($view) as $name => $value)
			header(sprintf('%s: %s', $name, $value));
		
		return $this->getBody($view);
	}
	
	protected function getCode(\AeFramework\Views\IView $view)
	{
		return $view->code();
	}
	
	protected function getHeaders(\AeFramework\Views\IView $view)
	{
		return $view->headers();
	}
	
	protected function getBody(\AeFramework\Views\IView $view)
	{
		return $view->body();
	}
}