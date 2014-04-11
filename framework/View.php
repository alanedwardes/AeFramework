<?php
namespace ae\framework;

abstract class View
{
	public $router;
	abstract protected function render();
	abstract protected function cacheHash();
}