# Funk

Funk is a HTTP server implementation of PHPSGI.

- Funk supports php-fpm, apache2 php handler servers.


## Middleware

```php
use Funk\Environment;
use Funk\Middleware\TryCacheMiddleware;

$app = function(array $environment, array $response) {
    return [ 200,  ['Content-Type' => 'text/html' ], 'Hello World' ];
};
$middleware = new TryCatchMiddleware($app);


$env = Environment::createFromGlobals();
$response = $middleware($env, [200, [], []]);
```

## Contributing

### Testing XHProf Middleware


Define your XHPROF_ROOT in your `phpunit.xml`, you can copy `phpunit.xml.dist` to `phpunit.xml`,
for example:

```xml
  <php>
    <env name="XHPROF_ROOT" value="/Users/c9s/src/php/xhprof"/>
  </php>
```

