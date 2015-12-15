# JSON-RPC Auth Extension

This is an authentication and authorization extension for the [php-json-rpc](https://github.com/datto/php-json-rpc) library. It provides the ability to authorize JSON-RPC requests before they reach the endpoint. 

Examples
--------
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

Requirements
------------
* PHP >= 5.3

Installation
------------
```javascript
"require": {
  "datto/json-rpc-auth": "~4.0"
}
```   

License
-------
This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html).

Author
------
Written by Chad Kosie and [Philipp C. Heckel](https://github.com/binwiederhier).
