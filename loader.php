<?php
# Set the namespace
namespace ae;

# For benchmarking (X-Generate header)
define('AE_START_LOAD_TIME', microtime(true));

# Framework Specific
require_once 'framework/Util.php';
require_once 'framework/HttpCode.php';
require_once 'framework/Router.php';
require_once 'framework/CachedRouter.php';
require_once 'framework/Cache.php';
require_once 'framework/FileCache.php';
require_once 'framework/MemcacheCache.php';
require_once 'framework/View.php';
require_once 'framework/Mapper.php';
require_once 'framework/TextView.php';

# Vendor Specific
require_once 'vendor/Twig.php';