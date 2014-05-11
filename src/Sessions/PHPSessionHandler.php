<?php
namespace Carbo\Sessions;

class PHPSessionHandler extends SessionHandler
{
	const Index = __NAMESPACE__;
	
	public function __construct($name = '', $timeout = 0)
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			if ($name) session_name($name);
			
			if ($timeout) session_set_cookie_params($timeout);
			
			session_start();
		}
	}
	
	public function &getSession()
	{
		if (!isset($_SESSION[self::Index]))
		{
			$_SESSION[self::Index] = [];
		}
		
		return $_SESSION[self::Index];
	}
	
	public function get($key)
	{
		if (isset($this->getSession()[$key]))
		{
			return $_SESSION[self::Index][$key];
		}
		
		return null;
	}
	
	public function set($key, $value)
	{
		$this->getSession()[$key] = $value;
	}
	
	public function destroy()
	{
		if (session_status() == PHP_SESSION_ACTIVE)
		{
			session_destroy();
		}
	}
}