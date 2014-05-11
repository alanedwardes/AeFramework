<?php
namespace Carbo\Routing;

use Carbo\ClassFactory;
use Carbo\Views\View;
use Carbo\Views\IRunner;
use Carbo\Mapping\Mapper;
use Carbo\Http as Http;

class Router
{
	public $mappers = [];
	public $error_views = [];
	public $path = '';
	
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
				$this->serveFromMapper($mapper);
				return;
			}
		}
		
		throw new Http\CodeException(Http\Code::NotFound);
	}
	
	protected function serveFromMapper($mapper)
	{
		$target = $this->constructMapperTarget($mapper->target);
	
		if ($target instanceof Router)
		{
			$target->despatch($mapper->remaining);
		}
		elseif ($target instanceof View)
		{
			$this->serveView($target, $mapper->params);
		}
	}
	
	public function despatch($path = null)
	{
		# If $path is null, use SERVER['REQUEST_URI']
		$this->path = ($path !== null ? $path : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		
		try
		{
			$this->findViewFromMappers($path);
		}
		catch (Http\CodeException $e)
		{
			$this->serveError($e->getCode());
		}
	}
	
	# Protected functions
	protected function serveError($code)
	{
		if (isset($this->error_views[$code]))
		{
			$view = $this->constructMapperTarget($this->error_views[$code]);
			$view->code = $code;
			$this->serveView($view);
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
			return ClassFactory::constructClassAndFillMembers($target[0], array_slice($target, 1));
		}
		else
		{
			return ClassFactory::constructClass($target[0]);
		}
	}
	
	protected function serveView(View $view, array $mapper_params = [])
	{
		# Call the request
		$view->request(@$_SERVER['REQUEST_METHOD'], $mapper_params);
		
		# Get the response and save it
		$response = $this->getResponse($view);
		
		# Set response status code
		http_response_code($view->code);
		
		# Set response headers
		if (!headers_sent())
			foreach ($view->headers as $name => $value)
				header(sprintf('%s: %s', $name, $value));
		
		# Set response body
		echo $response;
		
		# If the view is a task runner,
		# disconnect and run the task
		if ($view instanceof IRunner)
		{
			fastcgi_finish_request();
			$view->task();
		}
	}
	
	protected function getResponse(View $view)
	{
		return $view->response();
	}
}