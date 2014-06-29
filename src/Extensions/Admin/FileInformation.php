<?php
namespace Carbo\Extensions\Admin;

class FileInformation
{
	public $name = '';
	public $path = '';
	public $parent = null;
	
	public function __construct($name = null, FolderInformation $parent)
	{
		$this->parent = $parent;
		$this->name = $name;
		$this->path = $this->getPath();
	}
	
	public function getPath()
	{
		return trim($this->parent ? $this->parent->path . DIRECTORY_SEPARATOR . $this->name : $this->name, '/');
	}
	
	public function size()
	{
		return filesize($this->path);
	}
	
	public function isWritable()
	{
		return is_writable($this->path);
	}
	
	public function __toString()
	{
		return $this->name;
	}
}