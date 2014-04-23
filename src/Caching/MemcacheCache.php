<?php
namespace AeFramework\Caching;

class MemcacheCache extends Cache
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
	
	public function set($key, $data, $expire = 0)
	{
		return $this->mc->set($key, $data, null, $expire);
	}
}