AeFramework
===========

A fast, light and succinct PHP MVC framework. *By no means stable or secure yet - use at your own risk.*

Basic Usage
-----------

```php
require_once 'AeFramework/loader.php';

# Create a router
$router = new ae\framework\Router;

# Map URL "/" to a simple text view saying "Hello, world!"
$router->route('/', new ae\vendor\Text('Hello, world!'));

# Set a 404 view
$router->error(ae\framework\HttpCode::NotFound, new ae\vendor\Text('File not found'));

# Optional - Set up Memcache
$router->cacheProvider(new ae\vendor\Memcache, 'application-unique-cache-key');

# Despatch
$router->despatch();
```
