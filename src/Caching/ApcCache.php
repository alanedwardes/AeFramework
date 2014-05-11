<?php
namespace Carbo\Caching;

class ApcCache extends Cache
{
	public function get($key)
	{
		return apc_fetch($key);
	}
	
	public function set($key, $data, $expire = 0)
	{
		return apc_add($key, $data, $expire);
	}
}