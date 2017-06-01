<?php
use Funk\Middleware\XHProfMiddleware;
use Funk\Testing\TestUtils;

class XHProfMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @requires extension xhprof
     */
    public function testXHProfMiddleware()
    {
        $xhprofRoot = getenv('XHPROF_ROOT');

        if (!$xhprofRoot) {
            return $this->markTestSkipped("'XHPROF_ROOT' environment variable is unset.");
        }

        $testing = $this;
        $app = function(array & $environment, array $response) use ($testing) {
            $cnt = 0;
            for ($i = 0 ; $i < 10000; $i++) {
                $cnt++;
            }
            return [200, [], ['Hell Yeah']];
        };

        $env = TestUtils::createEnv('GET', '/');
        $m = new XHProfMiddleware($app, [ 
            'flags' => XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY,
            'root' => getenv('XHPROF_ROOT'),
            'prefix' => 'pux_testing_',
        ]);
        $response = $m($env, []);
        $this->assertNotEmpty($response);
    }
}

