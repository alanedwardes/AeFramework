<?php
namespace AeFramework\Views;

class TextView extends View
{
	public $text;
	public $code;
	
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