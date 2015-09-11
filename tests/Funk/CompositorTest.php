<?php
use Funk\Compositor;
use Funk\Testing\Utils;
use Funk\App\MuxApp;
use Pux\RouteRequest;

class CompositorTest extends PHPUnit_Framework_TestCase
{

    public function testCompositorInvoke()
    {
        $compositor = new Compositor;
        $compositor->enable('Funk\\Middleware\\TryCatchMiddleware', [ 'throw' => true ]);
        $compositor->enable(function($app) {
            return function(array & $environment, array $response) use ($app) { 
                $environment['middleware.app'] = true;
                return $app($environment, $response);
            };
        });

        $compositor->app(function(array & $environment, array $response) {
            $request = RouteRequest::createFromEnv($environment);
            if ($request->pathStartWith('/foo')) {

            }

            $response[0] = 200;
            return $response;
        });

        $env = Utils::createEnv('GET', '/foo/bar');
        $response = $compositor($env, []);
        $this->assertNotEmpty($response);
    }

    public function testCompositorWithRecursiveUrlMap()
    {
        $appcomp = new Compositor;
        $appcomp->app(MuxApp::mountWithUrlMap([
            "/hack" => new Compositor(MuxApp::mountWithUrlMap([ 
                "/foo" => ['ProductController', 'fooAction'],
                "/bar" => ['ProductController', 'barAction'],
            ])),
        ]));
        $app = $appcomp->wrap();

        $env = Utils::createEnv('GET', '/hack/foo');
        $response = $app($env, []);
        $this->assertNotEmpty($response);
        $this->assertEquals('foo',$response);
    }

    public function testCompositorWithUrlMap()
    {
        $compositor = new Compositor;
        $compositor->app(MuxApp::mountWithUrlMap([ 
            "/foo" => ['ProductController', 'fooAction'],
            "/bar" => ['ProductController', 'barAction'],
        ]));
        $app = $compositor->wrap();
        $this->assertInstanceOf('Funk\\App\\MuxApp', $app,
            'When there is only one app and no middleware, the returned type should be just MuxApp');
        $env = Utils::createEnv('GET', '/foo');
        $response = $app($env, []);
        $this->assertNotEmpty($response);
        $this->assertEquals('foo',$response);
    }



    public function testCompositor()
    {
        $compositor = new Compositor;
        $compositor->enable('Funk\\Middleware\\TryCatchMiddleware', [ 'throw' => true ]);
        $compositor->enable(function($app) {
            return function(array & $environment, array $response) use ($app) { 
                $environment['middleware.app'] = true;
                return $app($environment, $response);
            };
        });

        // TODO
        // $compositor->mount('/foo', function() {  });

        $compositor->app(function(array & $environment, array $response) {
            $request = RouteRequest::createFromEnv($environment);

            // $mux = new Mux;

            if ($request->pathStartWith('/foo')) {

            }

            $response[0] = 200;
            return $response;
        });
        $app = $compositor->wrap();

        $env = Utils::createEnv('GET', '/foo');
        $res = [  ];
        $res = $app($env, $res);
        $this->assertNotEmpty($res);
        $this->assertEquals(200, $res[0]);
    }
}

