<?php
namespace Carbo\Extensions\Admin\Views;

class StatsView extends AdminView implements \Carbo\Views\IAuthenticated
{
	public function response(array $template_params = [])
	{
		parent::response($template_params);
	}
}