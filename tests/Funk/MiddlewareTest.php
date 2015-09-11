<?php
use Funk\Middleware;
use Funk\Testing\Utils;
use Funk\Compositor;
use Funk\Middleware\TryCatchMiddleware;


class MiddlewareTest extends PHPUnit_Framework_TestCase
{


    public function testMiddleware()
    {
        $app = function(array $environment, array $response) {
            return $response;
        };

        $middleware = new TryCatchMiddleware($app);
        $middleware2 = new TryCatchMiddleware($middleware);

        $env = Utils::createGlobals('GET', '/foo');
        $response = $middleware2($env, [200, [], []]);
        $this->assertNotEmpty($response);
    }
}




