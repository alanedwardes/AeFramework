AeFramework
===========

A fast, light and succinct PHP MVC framework.

Basic Usage
-----------

```php
require_once 'AeFramework/loader.php';

# Create a router
$router = new ae\framework\Router;

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route('/', new ae\vendor\Text('Hello, world!'));

# Set a 404 view
$router->error(ae\framework\Router::NotFound, new ae\vendor\Text('File not found'));

# Optional - Set up Memcache
$router->cacheProvider(new ae\vendor\Memcache, 'application-unique-cache-key');

# Despatch
$router->despatch();
```