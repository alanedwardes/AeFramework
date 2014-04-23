<?php
namespace AeFramework\Caching;

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
		$cache_file = $this->cacheFile($key);
		if (file_exists($cache_file))
		{
			$expire_file = $this->getExpireFile($cache_file);
			if (file_exists($expire_file))
			{
				if (file_get_contents($expire_file) < time())
				{
					unlink($cache_file);
					unlink($expire_file);
					return false;
				}
			}
		
			return file_get_contents($cache_file);
		}
		else
		{
			return false;
		}
	}
	
	public function set($key, $data, $expire = 0)
	{
		$cache_file = $this->cacheFile($key);
		
		file_put_contents($cache_file, $data);
		
		if ($expire > 0)
		{
			$expire_file = $this->getExpireFile($cache_file);
			file_put_contents($expire_file, time() + $expire);
		}
	}
	
	private function getExpireFile($cache_file)
	{
		return sprintf('%s-expire', $cache_file);
	}
}