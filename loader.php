<?php
# Set the namespace
namespace AeFramework;

# For benchmarking (X-Generate header)
define('AE_START_LOAD_TIME', microtime(true));

spl_autoload_register(function($name){
	# If this class is in the AeFramework namespace
	if (substr($name, 0, strlen(__NAMESPACE__)) == __NAMESPACE__)
	{
		return;
		# Truncate the namespace, including the directory separator
		$name = substr($name, strlen(__NAMESPACE__) + 1);
		
		#Require the final file
		require_once sprintf('src%s%s.php', DIRECTORY_SEPARATOR, $name);
	}
});

# Vendor Specific
require_once 'vendor/Twig.php';