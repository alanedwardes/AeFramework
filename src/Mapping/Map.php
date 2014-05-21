<?php
namespace Carbo\Mapping;

class Map
{
	const MAPPER_TYPE_REGEX = 'r';

	public static function create(\Carbo\Routing\Router &$router, array $map = [])
	{
		foreach ($map as $mapping)
		{
			$route = $mapping[0];
			
			if ($route instanceof Mapper)
			{
				$router->route($route);
			}
			else
			{
				$view = array_slice($mapping, 1);
				
				if (is_numeric($route))
				{
					$router->error($route, $view);
				}
				else if (!is_string($view[0]))
				{
					$router->route(self::constructMapperFromRoute($route, $view[0]));
				}
				else
				{
					$router->route(self::constructMapperFromRoute($route, $view));
				}
			}
		}
	}
	
	private static function constructMapperFromRoute(&$route, &$view)
	{
		switch (substr($route, 0, 1))
		{
			case self::MAPPER_TYPE_REGEX:
				return new RegexMapper(substr($route, 1), $view);
				break;
			default:
				return new StringMapper($route, $view);
				break;
		}
	}
}