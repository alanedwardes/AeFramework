<?php
namespace Carbo\Extensions\Admin;

class FolderInformation
{
	public $name = '';
	public $parent = null;
	public $folders = [];
	public $path = '';
	
	public function __construct($name = null, FolderInformation $parent = null)
	{
		$this->parent = $parent;
		$this->name = $name;
		$this->path = $this->getPath();
		
		$this->findChildren();
	}
	
	public function getPath()
	{
		return trim($this->parent ? $this->parent->path . DIRECTORY_SEPARATOR . $this->name : $this->name, '/');
	}
	
	public function findChildren()
	{
		foreach (glob(getcwd() . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) as $child)
		{
			$this->folders[basename($child)] = new FolderInformation(basename($child), $this);
		}
	}
	
	public function isWritable()
	{
		return is_writable($this->path);
	}
	
	public function __toString()
	{
		return Utilities::formatObjectName($this->name);
	}
}