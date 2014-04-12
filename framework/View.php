<?php
namespace ae\framework;

abstract class View
{
	public $router;
	public $code = HttpCode::Ok;
	abstract protected function render();
	abstract protected function cacheHash();
}