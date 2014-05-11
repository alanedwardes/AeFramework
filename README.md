Carbo
=====

A fast, light and succinct PHP web framework. *By no means stable or secure yet - use at your own risk.*

Basic Usage
-----------

```php
require_once 'carbo/loader.php';

# Create a cache
# for memcache: $cache = new Carbo\Caching\Memcache;
$cache = new Carbo\Caching\FileCache('.\cache');

# Create a cached router
# for no cache: $router = new Carbo\Routing\Router;
$router = new Carbo\Routing\CachedRouter($cache);

# Create a simple view
# for twig: $hello_view = new Carbo\Views\TwigView('templates/hello_world.html');
$hello_view = new Carbo\Views\TextView('Hello, world!');

# Create a simple path mapper
# for a regex: $mapper = new Carbo\Mapping\RegexMapper('^/posts/(?P<slug>.*)/$', $this->hello_view);
$mapper = new Carbo\Mapping\StringMapper('/', $hello_view);

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route($mapper);

# Create a simple not found view
$notfound_view = new Carbo\Views\TextView('File not found');

# Set the not found view
$router->error(Carbo\Http\Code::NotFound, $notfound_view);

# Despatch
echo $router->despatch();
```
