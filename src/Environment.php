<?php
namespace Funk;
use Funk\Buffer\SAPIInputBuffer;

/**
 * Environment array factory method
 *
 *    Environment::createFromGlobals($GLOBALS);
 */
class Environment
{

    /**
     * Create from globals array
     *
     * @param array $array
     * @return array
     */
    static public function createFromArray(array $array)
    {
        $env = $array['_SERVER'];
        $env['parameters'] = $env['_REQUEST'] = $array['_REQUEST'];
        $env['body_parameters'] = $env['_POST']    = $array['_POST'];
        $env['query_parameters'] = $env['_GET']     = $array['_GET'];
        $env['_COOKIE']  = $array['_COOKIE'];
        $env['_SESSION'] = $array['_SESSION'];
        return $env;
    }


    /**
     * Create environment array from SAPI globals
     *
     * @return array
     */
    static public function createFromGlobals()
    {
        $env = $GLOBALS['_SERVER'];
        $env['parameters'] = $env['_REQUEST'] = $GLOBALS['_REQUEST'];
        $env['body_parameters'] = $env['_POST']    = $GLOBALS['_POST'];
        $env['query_parameters'] = $env['_GET']     = $GLOBALS['_GET'];
        $env['phpsgi.input'] = new SAPIInputBuffer;

        if (isset($GLOBALS['_COOKIE'])) {
            $env['_COOKIE']  = $GLOBALS['_COOKIE'];
        }

        // When session is not started, we don't have _SESSION in globals
        if (isset($GLOBALS['_SESSION'])) {
            $env['_SESSION'] = $GLOBALS['_SESSION'];
        }
        return $env;
    }
}





