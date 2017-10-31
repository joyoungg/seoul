<?php

namespace App;

class Route
{
    protected $routes = [];
    protected $responseStatus = '200 OK';
    protected $responseContentType = 'application/json; charset=utf-8';
    protected $responseBody = '';

    public function addRoute($routePath, $routeCallback)
    {
        $this->routes[$routePath] = $routeCallback->bindTo($this, __CLASS__);
    }

    public function dispatch($currentPath)
    {
        foreach ($this->routes as $routePath => $callback) {
            if ($routePath === $currentPath) {
                $callback();
            }
        }
        header('HTTP/1.1 ' . $this->responseStatus);
        header('Content-type ' . $this->responseContentType);
        header('Content-length ' . mb_strlen($this->responseBody));
        header('Access-Control-Allow-Origin: *');
        echo $this->responseBody;
    }
}
