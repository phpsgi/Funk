<?php
namespace Funk\Server;
use Funk\Server\BaseServer;
use Event;
use EventBase;
use EventBuffer;
use EventHttp;
use EventHttpConnection;
use EventHttpRequest;

class EventHttpServer extends BaseServer
{

    public function requestCloseHandler($connection)
    {
        echo __FUNCTION__, PHP_EOL;
    }


    public function handleRequest($request, $dummy)
    {
        echo get_class($request), "\n";
        echo "URI: ", $request->getUri(), PHP_EOL;

        $headers = $request->getInputHeaders();
        var_dump($headers);
        
        $conn = $request->getConnection();
        // $conn->setCloseCallback([$this, 'requestCloseHandler'], NULL);

        /*
        By enabling Event::READ we protect the server against unclosed conections.
        This is a peculiarity of Libevent. The library disables Event::READ events
        on this connection, and the server is not notified about terminated
        connections.

        So each time client terminates connection abruptly, we get an orphaned
        connection. For instance, the following is a part of `lsof -p $PID | grep TCP`
        command after client has terminated connection:

        57-php     15057 ruslan  6u  unix 0xffff8802fb59c780   0t0  125187 socket
        58:php     15057 ruslan  7u  IPv4             125189   0t0     TCP *:4242 (LISTEN)
        59:php     15057 ruslan  8u  IPv4             124342   0t0     TCP localhost:4242->localhost:37375 (CLOSE_WAIT)

        where $PID is our process ID. 

        The following block of code fixes such kind of orphaned connections.
        */
        $bev = $request->getBufferEvent();
        $bev->enable(Event::READ);

        // $request->addHeader('Content-Type', 'text/plain', EventHttpRequest::OUTPUT_HEADER);

        /*
        $request->addHeader('Content-Type',
            'multipart/x-mixed-replace;boundary=boundarydonotcross',
            EventHttpRequest::OUTPUT_HEADER);
        */

        $buf = new EventBuffer();
        $buf->add("Hello World\r\n");

        $request->sendReply(200, "OK");
        $request->sendReplyChunk($buf);
        $request->sendReplyEnd();

        // $request->closeConnection();
    }


    /**
     * Start listening
     */
    public function listen()
    {
        echo "Starting server at http://{$this->address}:{$this->port}...\n";
        $base = new EventBase();
        $http = new EventHttp($base);
        $http->setDefaultCallback([$this, 'handleRequest'], NULL);
        $ret = $http->bind($this->address, $this->port);
        if ($ret === false) {
            throw new Exception("EventHttp::bind failed on {$this->address}:{$this->port}");
        }
        $base->loop();
    }



}





