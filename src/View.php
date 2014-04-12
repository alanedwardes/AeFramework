<?php
namespace AeFramework;

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