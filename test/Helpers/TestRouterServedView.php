<?php
class TestRouterServedView extends \Carbo\Routing\Router
{
	public $served_view;
	
	public function serveView(\Carbo\Views\View $view, array $mapper_params = [])
	{
		$this->served_view = $view;
	}
}