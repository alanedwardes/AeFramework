<?php
namespace ae\framework;

abstract class Mapper
{
	public $mapping;
	public $view;
	
	public function __construct($mapping, View $view)
	{
		$this->mapping = $mapping;
		$this->view = $view;
	}
	
	protected abstract function match($path);
}

class StringMapper extends Mapper
{
	public function match($path)
	{
		# Perform a quick and simple string comparison
		return $this->mapping === $path;
	}
}

class RegexMapper extends Mapper
{
	public function __construct($mapping, View $view)
	{
		# First, escape /
		$mapping = str_replace('/', '\/', $mapping);
		
		# Next, encapsulate with slashes
		$mapping = sprintf('/%s/', $mapping);
		
		# Fianally, pass it back
		parent::__construct($mapping, $view);
	}
	
	private function removeNumericGroups(&$groups)
	{
		# Loop the groups
		foreach ($groups as $name => $group)
			# If the key is an integer
			if (is_int($name))
				# Unset it
				unset($groups[$name]);
	}
	
	public function match($path)
	{
		# An array to store matched groups.
		# Example: ^/posts/(?<group_name>.*)/$
		$groups = array();
		
		# Perform the match
		if (preg_match($this->mapping, $path, $groups) === false)
			return false;
		
		# Remove numeric groups, so the output
		# is a string-indexed array of the
		# initial group names (see http://www.php.net/preg_match)
		$this->removeNumericGroups($groups);
		
		# Pass to the view
		$this->view->mapper_params = $groups;
		
		return true;
	}
}