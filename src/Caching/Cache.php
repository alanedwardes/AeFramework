<?php
namespace Carbo\Caching;

abstract class Cache
{
	abstract protected function get($key);
	abstract protected function set($key, $data, $expire = 0);
}