<?php
namespace AeFramework;

abstract class RedirectView implements IView
{
	public $location;
	private $mapper_params;
	
	function __construct($location)
	{
		$this->location = $location;
	}
	
	function map($params = [])
	{
		$this->mapper_params = $params;
	}
	
	abstract public function code();
	
	public function headers()
	{
		return ['Location' => vsprintf($this->location, $this->mapper_params)];
	}
	
	function body()
	{
		return null;
	}
}