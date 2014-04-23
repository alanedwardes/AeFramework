<?php
namespace AeFramework;

class Despatcher
{
	private $routers;
	private $path;

	public function addRouter($expression, Router $router)
	{
		$this->routers[] = [Util::formatPathExpression($expression), $router];
	}

	public function despatch($path = null)
	{
		$this->path = ($path != null ? $path : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		
		return $this->filterRouters();
	}
	
	public function filterRouters()
	{
		foreach ($this->routers as $router_data)
		{
			$expression = $router_data[0];
			$router = $router_data[1];
			if (preg_match($expression, $this->path, $matches) !== 0)
			{
				$router_path = preg_replace($expression, '', $this->path);
				return $router->despatch($router_path);
			}
		}
	}
}