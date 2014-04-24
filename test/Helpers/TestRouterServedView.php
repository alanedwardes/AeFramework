<?php
class TestRouterServedView extends \AeFramework\Routing\Router
{
	public $served_view;
	
	public function serveView(\AeFramework\Views\View $view, array $mapper_params = [])
	{
		$this->served_view = $view;
	}
}