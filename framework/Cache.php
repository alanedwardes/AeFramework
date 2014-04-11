<?php
namespace ae\framework;

abstract class Cache
{
	abstract protected function get($key);
	abstract protected function set($key, $data);
}