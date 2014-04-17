<?php
class TestRouterServedView extends AeFramework\Router
{
	public $served_view;
	
	public function serveView(AeFramework\IView $view)
	{
		$this->served_view = $view;
	}
}