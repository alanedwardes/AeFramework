<?php
namespace Carbo\Views;

abstract class RedirectView extends View
{
	public $location;
	
	function __construct($location)
	{
		$this->location = $location;
	}
	
	function request($verb, array $params = [])
	{
		$this->headers['Location'] = vsprintf($this->location, $params);
	}
	
	function response()
	{
		return null;
	}
}