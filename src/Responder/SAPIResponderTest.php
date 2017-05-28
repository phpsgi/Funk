<?php

namespace Funk\Responder;

use Funk\Responder\SAPIResponder;

class SAPIResponderTest extends \PHPUnit\Framework\TestCase
{

    public function testStringResponse()
    {
        $fd = fopen('php://memory', 'r+');
        $responder = new SAPIResponder($fd);
        $responder->respond('Hello World');

        rewind($fd);
        $s = fgets($fd);
        $this->assertEquals('Hello World', $s);
        fclose($fd);
    }

    public function testArrayResponse()
    {
        $fd = fopen('php://memory', 'r+');
        $responder = new SAPIResponder($fd);
        $responder->respond([ 200, [ 'Content-Type: text/plain' ], 'Hello World' ]);

        rewind($fd);
        $s = fgets($fd);
        $this->assertEquals('Hello World', $s);

        fclose($fd);
    }

    public function testHeaderListResponse()
    {
        $fd = fopen('php://memory', 'r+');
        $responder = new SAPIResponder($fd);
        $responder->respond([ 200, [ 'Content-Type: text/plain', 'X-Foo: Bar', ['X-Bar' => 'Zoo'] ], 'Hello World' ]);

        rewind($fd);
        $s = fgets($fd);
        $this->assertEquals('Hello World', $s);

        fclose($fd);
    }

    public function testHeaderAssocArrayResponse()
    {
        $fd = fopen('php://memory', 'r+');
        $responder = new SAPIResponder($fd);
        $responder->respond([ 200, [ 'Content-Type' =>  'text/plain', 'X-Foo' => 'Bar', ['X-Bar' => 'Zoo'] ], 'Hello World' ]);

        rewind($fd);
        $s = fgets($fd);
        $this->assertEquals('Hello World', $s);

        fclose($fd);
    }
}

