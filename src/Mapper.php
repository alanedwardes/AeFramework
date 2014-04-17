<?php
namespace AeFramework;

abstract class Mapper
{
	public $mapping = '';
	public $view = null;
	public $params = [];
	
	public function __construct($mapping, $view)
	{
		$this->mapping = $mapping;
		$this->view = $view;
	}
	
	protected abstract function match($path);
}