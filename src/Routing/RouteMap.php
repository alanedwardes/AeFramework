<?php
namespace AeFramework\Routing;

use AeFramework\Mapping as Mapping;

class RouteMap
{
	const MAPPER_TYPE_REGEX = 'r';

	public static function map(Router &$router, array $map = [])
	{
		foreach ($map as $mapping)
		{
			$route = $mapping[0];
			
			if ($route instanceof Mapping\Mapper)
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
				return new Mapping\RegexMapper(substr($route, 1), $view);
				break;
			default:
				return new Mapping\StringMapper($route, $view);
				break;
		}
	}
}