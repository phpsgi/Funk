<?php
use Funk\Compositor;
use Funk\App\MuxApp;

class MuxAppTest extends PHPUnit_Framework_TestCase
{
    public function testMuxApp()
    {
        $app = MuxApp::mountWithUrlMap([
            "/foo" => ['ProductController', 'fooAction'],
            "/bar" => ['ProductController', 'barAction'],
        ]);
        $this->assertNotNull($app);
        $this->assertInstanceOf('Funk\App\MuxApp',$app);
    }




}

