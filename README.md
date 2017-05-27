# Funk

[![Build Status](https://travis-ci.org/phpsig/funk.svg?branch=master)](https://travis-ci.org/phpsig/funk)
[![Coverage Status](https://img.shields.io/coveralls/phpsig/funk.svg)](https://coveralls.io/r/phpsig/funk)
[![Latest Stable Version](https://poser.pugx.org/phpsig/funk/v/stable.svg)](https://packagist.org/packages/phpsig/funk) 
[![Total Downloads](https://poser.pugx.org/phpsig/funk/downloads.svg)](https://packagist.org/packages/phpsig/funk) 
[![Monthly Downloads](https://poser.pugx.org/phpsig/funk/d/monthly)](https://packagist.org/packages/phpsig/funk)
[![Daily Downloads](https://poser.pugx.org/phpsig/funk/d/daily)](https://packagist.org/packages/phpsig/funk)
[![Latest Unstable Version](https://poser.pugx.org/phpsig/funk/v/unstable.svg)](https://packagist.org/packages/phpsig/funk) 
[![License](https://poser.pugx.org/phpsig/funk/license.svg)](https://packagist.org/packages/phpsig/funk)
[![Join the chat at https://gitter.im/phpsig/funk](https://badges.gitter.im/phpsig/funk.svg)](https://gitter.im/phpsig/funk?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Works On My Machine](https://cdn.rawgit.com/nikku/works-on-my-machine/v0.2.0/badge.svg)](https://github.com/nikku/works-on-my-machine)
[![Made in Taiwan](https://img.shields.io/badge/made%20in-taiwan-green.svg)](README.md)

Funk is an implementation of PHPSGI. It supports HTTP servers implemented with PHP SAPI (Apache2 `mod_php`, `php-fpm`, `fastcgi`), therefore you can integrate your application with Funk and switch to different HTTP server implementation.

## Components

- HTTP server (with event extension or `socket_select`)
- SAPI support (php-fpm, apache2 php handler servers)
- Middlewares
- Middleware Compositor
- A Simple Mux Builder (integrated with Pux)


### Environment

```php
// This creates $env array from $_SERVER, $_REQUEST, $_POST, $_GET ... 
$env = Environment::createFromGlobals();
```

### Application

```php
$app = function(array & $environment, array $response) {
    return [ 200, [ 'Content-Type' => 'text/plain' ], 'Hello World' ];
};
```


### Responder

#### SAPIResponder

You can integrate your application with SAPIResponder to support Apache2 php handler / php-fpm / fastcgi.

```php
use Funk\Responder\SAPIResponder;

$fd = fopen('php://output', 'w');
$responder = new SAPIResponder($fd);
$responder->respond([ 200, [ 'Content-Type: text/plain' ], 'Hello World' ]);
fclose($fd);
```


```php
use Funk\Responder\SAPIResponder;

$env = Environment::createFromGlobals();
$app = function(array & $environment, array $response) {
    return [ 200, [ 'Content-Type' => 'text/plain' ], 'Hello World' ];
};
$fd = fopen('php://output', 'w');
$responder = new SAPIResponder($fd);
$responder->respond($app($env, []));
fclose($fd);
```



### Middleware

- `Funk\Middleware\ContentNegotiationMiddleware`
- `Funk\Middleware\CORSMiddleware`
- `Funk\Middleware\GeocoderMiddleware`
- `Funk\Middleware\HeadMiddleware`
- `Funk\Middleware\TryCatchMiddleware`
- `Funk\Middleware\XHProfMiddleware`
- `Funk\Middleware\XHTTPMiddleware`


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

