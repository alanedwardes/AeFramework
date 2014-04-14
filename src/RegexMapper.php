<?php
namespace AeFramework;

class RegexMapper extends Mapper
{
	public function __construct($mapping, IView $view)
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
		if (preg_match($this->mapping, $path, $groups) === 0)
			return false;
		
		# Remove numeric groups, so the output
		# is a string-indexed array of the
		# initial group names (see http://www.php.net/preg_match)
		$this->removeNumericGroups($groups);
		
		# Pass to the view
		$this->view->map($groups);
		
		return true;
	}
}