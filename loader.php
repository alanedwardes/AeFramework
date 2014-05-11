<?php
# Set the namespace
namespace Carbo;

# Load vendor dependencies
require_once 'vendor/autoload.php';

spl_autoload_register(function($name){
	# If class ($name) is in the AeFramework namespace
	if (substr($name, 0, strlen(__NAMESPACE__)) == __NAMESPACE__)
	{
		# Truncate the namespace, including the directory separator
		$name = substr($name, strlen(__NAMESPACE__) + 1);
		
		$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
		
		# Require the final file
		require_once sprintf('src%s%s.php', DIRECTORY_SEPARATOR, $name);
	}
});
