<?php
namespace AeFramework\Views;

use AeFramework as ae;

abstract class View
{
	public $code = ae\HttpCode::Ok;
	public $headers = [];
	
	abstract public function request($verb, array $params);
	abstract public function response();
}