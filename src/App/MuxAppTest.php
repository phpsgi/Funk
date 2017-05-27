<?php

namespace Funk\App;

use Funk\Compositor;
use Funk\App\MuxApp;
use PHPUnit\Framework\TestCase;

use PHPSGI\App;

class MuxAppTest extends TestCase
{
    public function testMuxApp()
    {
        $app = MuxApp::mountWithUrlMap([
            "/foo" => ['ProductController', 'fooAction'],
            "/bar" => ['ProductController', 'barAction'],
        ]);
        $this->assertNotNull($app);
        $this->assertInstanceOf(MuxApp::class,$app);
        $this->assertInstanceOf(App::class, $app, 'Must be an instanceof PHPSGI App');
    }
}
