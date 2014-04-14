<?php
namespace AeFramework;

abstract class RedirectView implements IView
{
	public $location;
	
	function __construct($location)
	{
		$this->location = $location;
	}
	
	function map($params = [])
	{
	}
	
	abstract public function code();
	
	public function headers()
	{
		return [new HttpHeader('Location', $this->location)];
	}
	
	function body()
	{
		return null;
	}
}