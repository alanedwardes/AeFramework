<?php
namespace Carbo\Views;

class TextView extends View
{
	public $text;
	
	public function __construct($text)
	{
		$this->text = $text;
	}
	
	public function request($verb, array $params)
	{
		$this->headers['Content-Type'] = 'text/plain';
	}
	
	public function response()
	{
		return $this->text;
	}
}