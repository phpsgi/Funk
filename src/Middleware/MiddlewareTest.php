<?php
use PHPSGI\Middleware;
use Pux\RouteRequest;
use Funk\Testing\Utils;
use Funk\Compositor;
use Funk\Middleware\TryCatchMiddleware;


class MiddlewareTest extends \PHPUnit\Framework\TestCase
{


    public function testMiddleware()
    {
        $app = function(array $environment, array $response) {
            $request = RouteRequest::createFromEnv($environment);
            return $response;
        };

        $middleware = new TryCatchMiddleware($app);
        $middleware2 = new TryCatchMiddleware($middleware);

        // $request = RouteRequest::create('GET', '/path');
        $env = Utils::createGlobals('GET', '/foo');
        $response = $middleware2($env, [200, [], []]);
        $this->assertNotEmpty($response);
    }
}




