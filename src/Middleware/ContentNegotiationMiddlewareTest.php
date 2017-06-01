<?php

namespace Funk\Middleware;

use Funk\Middleware\ContentNegotiationMiddleware;
use Funk\Testing\TestUtils;
use Negotiation\Negotiator;

class ContentNegotiationMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    public function testContentNegotiationMiddleware()
    {
        $testing = $this;
        $app = function(array & $environment, array $response) use ($testing) {
            $testing->assertEquals('text/html', $environment['request.best_format']->getValue());
        };

        $env = TestUtils::createEnv('GET', '/');
        $env['HTTP_ACCEPT'] = 'text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8';
        $env['negotiation.priorities'] = array('text/html', 'application/json');
        $m = new ContentNegotiationMiddleware($app, new Negotiator);
        $response = $m->call($env, []);
    }
}

