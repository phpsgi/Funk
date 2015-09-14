<?php
namespace Funk\Server;
use Exception;
use Funk\Server\BaseServer;

class StreamSocketServer extends BaseServer
{
    protected $socket;

    protected $connections = [];

    public function listen()
    {
        echo "Starting server at http://{$this->address}:{$this->port}...\n";

        $this->socket = stream_socket_server($this->address . ':' . $this->port, $errNo, $errStr);
        if (!$this->socket) {
            throw new Exception("Can't connect socket: [$errNo] $errStr");
        }
        $this->connections[] = $this->socket;


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
    }
}
