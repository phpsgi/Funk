# Funk

Funk is an implementation of PHPSGI. It supports HTTP servers implemented with PHP SAPI (Apache2 `mod_php`, `php-fpm`, `fastcgi`), therefore you can integrate your application with Funk and switch to different HTTP server implementation.

## Components

- HTTP server (with event extension or `socket_select`)
- SAPI support (php-fpm, apache2 php handler servers)
- Middlewares
- Middleware Compositor
- A Simple Mux Builder (integrated with Pux)


## Application

```php
$app = function(array & $environment, array $response) {
    return [ 200, [ 'Content-Type' => 'text/plain' ], 'Hello World' ];
};
```

## Environment

```php
// This creates $env array from $_SERVER, $_REQUEST, $_POST, $_GET ... 
$env = Environment::createFromGlobals();
```


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

