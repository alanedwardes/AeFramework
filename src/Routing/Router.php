<?php
namespace AeFramework\Routing;

use AeFramework\ClassFactory;
use AeFramework\Views\View;
use AeFramework\Mapping\Mapper;
use AeFramework\Http as Http;

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
		{
			if ($mapper->match($this->path))
			{
				return $this->serveFromMapper($mapper);
			}
		}
		
		throw new Http\CodeException(Http\Code::NotFound);
	}
	
	protected function serveFromMapper($mapper)
	{
		$target = $this->constructMapperTarget($mapper->target);
	
		if ($target instanceof Router)
		{
			return $target->despatch($mapper->remaining);
		}
		elseif ($target instanceof View)
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
		catch (Http\CodeException $e)
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
			throw new Http\CodeException($code);
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
			return ClassFactory::constructClassAndFillMembers($target[0], [$target[1]]);
		}
		else
		{
			return ClassFactory::constructClass($target[0]);
		}
	}
	
	protected function serveView(View $view, array $mapper_params = [])
	{
		$view->request(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null, $mapper_params);
		$response = $this->getResponse($view);
	
		http_response_code($this->getCode($view));
		
		if (!headers_sent())
			foreach ($this->getHeaders($view) as $name => $value)
				header(sprintf('%s: %s', $name, $value));
		
		return $response;
	}
	
	protected function getCode(View $view)
	{
		return $view->code;
	}
	
	protected function getHeaders(View $view)
	{
		return $view->headers;
	}
	
	protected function getResponse(View $view)
	{
		return $view->response();
	}
}