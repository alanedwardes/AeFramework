AeFramework
===========

A fast, light and succinct PHP web framework. *By no means stable or secure yet - use at your own risk.*

Basic Usage
-----------

```php
require_once 'AeFramework/loader.php';

# Create a cache
# for memcache: $cache = new AeFramework\Memcache;
$cache = new AeFramework\FileCache('.\cache');

# Create a cached router
# for no cache: $router = new AeFramework\Router;
$router = new AeFramework\CachedRouter($cache);

# Create a simple view
# for twig: $hello_view = new AeFramework\TwigView('templates/hello_world.html');
$hello_view = new AeFramework\TextView('Hello, world!');

# Create a simple path mapper
# for a regex: $mapper = new AeFramework\RegexMapper('^/posts/(?P<slug>.*)/$', $this->hello_view);
$mapper = new AeFramework\StringMapper('/', $hello_view);

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route($mapper);

# Create a simple not found view
$notfound_view = new AeFramework\TextView('File not found', AeFramework\HttpCode::NotFound);

# Set the not found view
$router->error(AeFramework\HttpCode::NotFound, $notfound_view);

# Despatch
echo $router->despatch();
```
