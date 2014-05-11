<?php
namespace Carbo\Views;

abstract class View
{
	public $code = \Carbo\Http\Code::Ok;
	public $headers = [];
	
	abstract public function request($verb, array $params);
	abstract public function response();
}