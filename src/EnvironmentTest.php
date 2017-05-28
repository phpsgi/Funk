<?php

namespace Funk;

use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testCreateFromGlobal()
    {
        $env = Environment::createFromGlobals();
        $this->assertNotEmpty($env);
    }
}
