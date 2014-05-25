## Carbo

A fast, light and succinct PHP web framework.

Features:
* Routing, caching, authentication and session management capabilities
* Zero-configuration admin panel that traverses schemas and automatically discovers relationships
* Built-in templating using the [Twig](https://github.com/fabpot/Twig) library

### Basic Usage

```php
<?php
# Without composer: require_once 'carbo/loader.php';
require_once 'vendor/autoload.php';

# Create a router
$router = new Carbo\Routing\Router;

Carbo\Mapping\Map::create($router, [
	# Map / to a simple text view
	['/', 'Carbo\Views\TextView', 'Hello, world!'],
	# Map 404 to another simple text view
	[Carbo\Http\Code::NotFound, 'Carbo\Views\TextView', 'Not found.']
]);

# Despatch
echo $router->despatch();
```

### Composer
```json
{
	"requre": {
		"alanedwardes/carbo": "dev-master"
	}
}
```
