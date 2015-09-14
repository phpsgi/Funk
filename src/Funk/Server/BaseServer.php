<?php
namespace Funk\Server;


abstract class BaseServer
{
    protected $app;

    protected $address;

    protected $port;

    public function __construct(callable $app, $address = '0.0.0.0', $port = 3000)
    {
        $this->app = $app;
        $this->address = $address;
        $this->port = intval($port);
    }


    abstract public function listen();


}



