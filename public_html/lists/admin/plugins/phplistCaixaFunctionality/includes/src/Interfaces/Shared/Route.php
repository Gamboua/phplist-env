<?php

namespace phplist\Caixa\Functionality\Interfaces\Shared;

/**
 * Class Route
 *
 * @package phplist\Caixa\Functionality\Interfaces\Shared
 */
class Route
{
    private $routes;
    private $defaultRoute = 'index';

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function setDefaultRoute($defaultRoute)
    {
        $this->defaultRoute = $defaultRoute;
    }

    public function dispatch()
    {
        $route = isset($_GET['action']) ? $_GET['action'] : $this->defaultRoute;
        if (array_key_exists($route, $this->routes)) {
            call_user_func($this->routes[$route]);
        }
    }
}
