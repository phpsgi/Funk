<?php
namespace Funk;
use ReflectionClass;
use Closure;
use PHPSGI\Middleware;

class Compositor implements \PHPSGI\App
{

    /**
     * @var Middleware
     */
    protected $stacks = array();


    /**
     * @var callable
     */
    protected $app;

    protected $mapApp;

    /**
     * @var Closure
     */
    private $wrappedApp;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function enable($appClass)
    {
        if ($appClass instanceof Closure) {
            $this->stacks[] = $appClass;
        } else {
            $args = func_get_args();
            array_shift($args);
            $this->stacks[] = [$appClass, $args];
        }
        return $this;
    }

    public function app($app)
    {
        $this->app = $app;
        return $this;
    }

    public function wrap()
    {
        $app = $this->app;

        for ($i = count($this->stacks) - 1; $i > 0; $i--) {
            $stack = $this->stacks[$i];

            // middleware closure
            if (is_callable($stack)) {
                $app = $stack($app);
            } else {
                list($appClass, $args) = $stack;
                $refClass = new ReflectionClass($appClass);
                array_unshift($args, $app);
                $app = $refClass->newInstanceArgs($args);
            }
        }

        return $app;
    }

    public function call(array & $environment, array $response)
    {
        if ($app = $this->wrappedApp) {
            return $app($environment, $response);
        }
        $this->wrappedApp = $app = $this->wrap();
        return $app($environment, $response);
    }

    public function __invoke(array & $environment, array $response)
    {
        return $this->call($environment, $response);
    }
}
