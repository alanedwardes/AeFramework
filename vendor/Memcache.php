<?php
namespace ae\vendor;

class Memcache extends \ae\framework\Cache
{
	private $mc = null;

	public function __construct($host = 'localhost', $port = 11211)
	{
		if (!class_exists('\Memcache'))
			throw new \Exception("Can't find memcache. Please see http://www.php.net/manual/en/book.memcache.php to install it.");
		
		$this->mc = new \Memcache();
		$this->mc->connect($host, $port);
	}

	public function get($key)
	{
		return $this->mc->get($key);
	}
	
	public function set($key, $data)
	{
		$this->mc->set($key, $data);
	}
}