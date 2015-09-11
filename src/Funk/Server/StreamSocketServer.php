<?php
namespace Funk\Server;
use Exception;

class StreamSocketServer
{
    protected $bind;

    protected $socket;

    protected $connections = [];

    public function __construct($bind)
    {
        $this->bind = $bind;
        $this->socket = stream_socket_server($this->bind, $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Can't connect socket: [$errno] $errstr");
        }
        $this->connections[] = $this->socket;
    }

    public function listen(callable $callback)
    {

        while (1) {

            echo "connections:";
            var_dump( $this->connections );

            $reads = $this->connections;
            $writes = NULL;
            $excepts = NULL;
            $modified = stream_select($reads, $writes, $excepts, 5);
            if ($modified === false) {
                break;
            }

            echo "modified fd:";
            var_dump( $modified );


            echo "reads:";
            var_dump( $reads ); 

            foreach ($reads as $modifiedRead) {

                if ($modifiedRead === $this->socket) {
                    $conn = stream_socket_accept($this->socket);
                    fwrite($conn, "Hello! The time is ".date("n/j/Y g:i a")."\n");
                    $this->connections[] = $conn;
                } else {
                    $data = fread($modifiedRead, 1024);
                    var_dump($data);

                    if (strlen($data) === 0) { // connection closed
                        $idx = array_search($modifiedRead, $this->connections, TRUE);
                        fclose($modifiedRead);
                        if ($idx != -1) {
                            unset($this->connections[$idx]);
                        }
                    } else if ($data === FALSE) {
                        echo "Something bad happened";
                        $idx = array_search($modifiedRead, $this->connections, TRUE);
                        if ($idx != -1) {
                            unset($this->connections[$idx]);
                        }
                    } else {
                        echo "The client has sent :"; var_dump($data);
                        fwrite($modifiedRead, "You have sent :[".$data."]\n");
                        fclose($modifiedRead);

                        $idx = array_search($modifiedRead, $this->connections, TRUE);
                        if ($idx != -1) {
                            unset($this->connections[$idx]);
                        }
                    }
                }

            }
        }


        /*
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
         */
    }
}
