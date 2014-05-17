<?php
namespace Carbo\Extensions\Admin\Views;

class LogoutView extends \Carbo\Views\View implements \Carbo\Views\IAuthenticated
{
	public function request($verb, array $params = [])
	{
	}
	
	public function response()
	{
		$this->headers['Location'] = '..';
	}
}