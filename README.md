AeFramework
===========

A fast, light and succinct PHP web framework. *By no means stable or secure yet - use at your own risk.*

Basic Usage
-----------

```php
require_once 'AeFramework/loader.php';

use AeFramework as ae;

# Create a cache
# for memcache: $cache = new ae\Caching\Memcache;
$cache = new ae\Caching\FileCache('.\cache');

# Create a cached router
# for no cache: $router = new ae\Routing\Router;
$router = new ae\Routing\CachedRouter($cache);

# Create a simple view
# for twig: $hello_view = new ae\Views\TwigView('templates/hello_world.html');
$hello_view = new ae\Views\TextView('Hello, world!');

# Create a simple path mapper
# for a regex: $mapper = new ae\Mapping\RegexMapper('^/posts/(?P<slug>.*)/$', $this->hello_view);
$mapper = new ae\Mapping\StringMapper('/', $hello_view);

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route($mapper);

# Create a simple not found view
$notfound_view = new ae\Views\TextView('File not found', ae\HttpCode::NotFound);

# Set the not found view
$router->error(ae\HttpCode::NotFound, $notfound_view);

# Despatch
echo $router->despatch();
```
