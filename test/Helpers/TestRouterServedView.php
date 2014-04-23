<?php
class TestRouterServedView extends \AeFramework\Routing\Router
{
	public $served_view;
	
	public function serveView(\AeFramework\Views\IView $view)
	{
		$this->served_view = $view;
	}
}