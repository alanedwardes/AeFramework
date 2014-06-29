<?php
namespace Carbo\Extensions\Admin;

class FolderInformation
{
	public $name = '';
	public $path = '';
	public $parent = null;
	public $folders = [];
	public $files = [];
	public $items = 0;
	
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
		$children = glob(getcwd() . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . '*');
		
		foreach ($children as $child)
			if (is_dir($child))
				$this->folders[basename($child)] = new FolderInformation(basename($child), $this);
			else
				$this->files[basename($child)] = new FileInformation(basename($child), $this);
		
		$this->items = count($children);
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