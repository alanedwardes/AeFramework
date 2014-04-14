<?php
namespace AeFramework;

abstract class Mapper
{
	public $mapping;
	public $view;
	
	public function __construct($mapping, IView $view)
	{
		$this->mapping = $mapping;
		$this->view = $view;
	}
	
	protected abstract function match($path);
}