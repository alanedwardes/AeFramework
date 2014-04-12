<?php
namespace ae\framework;

abstract class View
{
	# An HTTP Status code to return
	public $code = HttpCode::Ok;
	
	# A map of parameters from the mapper
	public $mapper_params = array();
	
	# Path
	public $path = '';
	
	# Render method
	abstract protected function render();
	
	# Cache hash method - a key to
	# quickly calculate the freshness
	# of this view
	abstract protected function cacheHash();
}

class TextView extends View
{
	public $text;
	
	public function __construct($text)
	{
		$this->text = basename($text);
	}
	
	public function render()
	{
		return $this->text;
	}
	
	public function cacheHash()
	{
		return crc32($this->text);
	}
}