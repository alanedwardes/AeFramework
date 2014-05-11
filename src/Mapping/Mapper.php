<?php
namespace Carbo\Mapping;

abstract class Mapper
{
	public $mapping = '';
	public $target = null;
	public $params = [];
	public $remaining = '';
	
	public function __construct($mapping, $target)
	{
		$this->mapping = $mapping;
		$this->target = $target;
	}
	
	protected abstract function match($path);
}