AeFramework
===========

A fast, light and succinct PHP MVC framework. *By no means stable or secure yet - use at your own risk.*

Basic Usage
-----------

```php
require_once 'AeFramework/loader.php';

# Create a cache
# for memcache: $cache = new ae\framework\Memcache;
$cache = new ae\framework\FileCache('.\cache');

# Create a cached router
# for no cache: $router = new ae\framework\Router;
$router = new ae\framework\CachedRouter($cache);

# Create a simple view
# for twig: $hello_view = new ae\vendor\Twig('templates/hello_world.html');
$hello_view = new ae\framework\TextView('Hello, world!');

# Create a simple path mapper
# for a regex: $mapper = new ae\framework\RegexMapper('^/posts/(?P<slug>.*)/$', $this->hello_view);
$mapper = new ae\framework\StringMapper('/', $hello_view);

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route($mapper);

# Create a simple not found view
$notfound_view = new ae\framework\TextView('File not found');

# Set the not found view
$router->error(ae\framework\HttpCode::NotFound, $notfound_view);

# Despatch
$router->despatch();
```
