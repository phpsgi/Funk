<?php
namespace Funk\Server;
use Exception;

class StreamSocketServer
{
    protected $bind;

    protected $socket;

    public function __construct($bind)
    {
        $this->bind = $bind;
        $this->socket = stream_socket_server($this->bind, $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Can't connect socket: [$errno] $errstr");
        }
    }

    public function listen(callable $callback)
    {
        while ($conn = stream_socket_accept($this->socket)) {
            print "accepted " . stream_socket_get_name($conn, true) . "\n";

            $buffer = '';
            while (!preg_match('/\r?\n\r?\n/', $buffer)) {
                $buffer .= fread($conn, 2046); 
            }

            $callback($conn);
            fwrite($conn, date('c'));
            fclose($conn);
        }
        fclose($this->socket);
    }
}
