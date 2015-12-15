# json-rpc-auth

This is an authentication and authorization extension for the [php-json-rpc](https://github.com/datto/php-json-rpc) library.

Installation
------------
```javascript
"require": {
  "datto/json-rpc-auth": "~4.0"
}
```    

Usage
-----
First write an authentication `Handler`:

```php
namespace Datto\JsonRpc\Auth;

use Datto\JsonRpc;

class BasicAuthHandler implements Handler
{
    public function canHandle($method, $arguments)
    {
        return isset($_SERVER['PHP_AUTH_USER']);
    }

    public function authenticate($method, $arguments)
    {
        // Don't do this in production. Using '===' is vulnerable to timing attacks!
        return $_SERVER['PHP_AUTH_USER'] === 'phil' && $_SERVER['PHP_AUTH_PW'] === 'superpass!';
    }
}
```

Once you have that, just use it like this. This example uses the `Simple\Evaluator` (see [php-json-rpc-simple](https://github.com/datto/php-json-rpc-simple)) as underlying mapping mechanism:

```php
$authenticator = new Authenticator(array(
    new BasicAuthHandler(),
    // ...
));

$server = new Server(new Auth\Evaluator(new Simple\Evaluator(), $authenticator));
echo $server->reply('...');
```
