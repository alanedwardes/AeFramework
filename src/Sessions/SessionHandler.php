<?php
namespace AeFramework\Sessions;

abstract class SessionHandler
{
	public abstract function __construct($name = null, $timeout = 0);
	public abstract function get($key);
	public abstract function set($key, $value);
	public abstract function destroy();
	
	public function __set($key, $value) { return $this->set($key, $value); }
	public function __get($key) { return $this->get($key); }
}