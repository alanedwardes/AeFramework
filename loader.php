<?php
# Set the namespace
namespace AeFramework;

spl_autoload_register(function($name){
	# If class ($name) is in the AeFramework namespace
	if (substr($name, 0, strlen(__NAMESPACE__)) == __NAMESPACE__)
	{
		# Truncate the namespace, including the directory separator
		$name = substr($name, strlen(__NAMESPACE__) + 1);
		
		# Require the final file
		require_once sprintf('src%s%s.php', DIRECTORY_SEPARATOR, $name);
	}
});