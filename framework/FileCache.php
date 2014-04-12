<?php
namespace ae\framework;

class FileCache extends Cache
{
	private $cache_folder;
	
	public function __construct($cache_folder)
	{
		$this->cache_folder = realpath($cache_folder);
	}
	
	private function cacheFile($key)
	{
		return Util::joinPath($this->cache_folder, Util::checksum($key));
	}
	
	public function get($key)
	{
		$file = $this->cacheFile($key);
		
		if (file_exists($file))
		{
			return file_get_contents($file);
		}
		else
		{
			return false;
		}
	}
	
	public function set($key, $data)
	{
		$file = $this->cacheFile($key);
		
		file_put_contents($file, $data);
	}
}