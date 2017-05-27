<?php
namespace Funk;
use CLIFramework\Application;
use CLIFramework\Logger;
use Funk\Server\StreamSocketServer;
use Funk\Server\EventHttpServer;
use Funk\Console;

class Console extends Application
{
    public function init()
    {

    }

    public function options($opts)
    {

    }

    public function executeWrapper(array $args)
    {
        if (empty($args)) {
            $args = [ null ];
            // $args = ['app.phpsgi'];
        }
        list($appFile) = $args;


        $logger = new Logger;

        if ($appFile) { 
            if (!file_exists($appFile)) {
                die("File $appFile doesn't exist.");
            }
            $app = require $appFile;
        } else {
            $app = function(array & $environment, array $response) {
                return [ 200, [ 'Content-Type' => 'text/plain' ], 'Hello World' ];
            };
        }

        if (extension_loaded('event')) {

            $logger->info("Found 'event' extension, enabling EventHttpServer server.");
            $server = new EventHttpServer($app);

        } else {

            $logger->info("Falling back to StreamSocketServer server.");
            $server = new StreamSocketServer($app);
        }
        return $server->listen();
    }

}



