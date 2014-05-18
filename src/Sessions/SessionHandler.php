<?php
namespace Carbo\Sessions;

abstract class SessionHandler
{
	public abstract function id();
	public abstract function get($key);
	public abstract function set($key, $value);
	public abstract function destroy();
	
	public function __set($key, $value) { return $this->set($key, $value); }
	public function __get($key) { return $this->get($key); }
}