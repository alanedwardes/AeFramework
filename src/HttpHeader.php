<?php
namespace AeFramework;

class HttpHeader
{
	public $name;
	public $value;
	
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}
	
	public function __toString()
	{
		return sprintf('%s: %s', $this->name, $this->value);
	}
}