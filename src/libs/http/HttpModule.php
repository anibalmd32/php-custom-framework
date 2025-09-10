<?php

namespace App\libs\http;

use ReflectionClass;
use ReflectionMethod;
use App\attributes\Route;
use App\libs\http\Request;
use App\libs\http\Response;

final class HttpModule
{
    /**
     * @var array<array<string, mixed>> $routes
     */
    private array $routes = [];

    /**
     * Load controllers and their routes
     *
     * @param array<string> $controllers
     * @return void
     */
    public function loadControllers(array $controllers): void
    {
        /**
         * @var object $controller
         */
        foreach ($controllers as $controller) {
            $ref = new ReflectionClass($controller);

            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $route = $method->getAttributes(Route::class);

                if (count($route) > 0) {
                    $route          = $route[0];
                    $this->routes[] = [
                        'path'       => $route->getArguments()[0],
                        'method'     => $route->getArguments()[1],
                        'controller' => $controller,
                        'handler'    => $method->getName(),
                    ];
                }
            }

            $this->dispatchRoutes();
        }
    }

    /**
     * Dispatch routes based on the current request
     *
     * @return void
     */
    private function dispatchRoutes(): void
    {
        if (count($this->routes) > 0) {
            foreach ($this->routes as $route) {
                if ($_SERVER['REQUEST_METHOD'] === $route['method'] && $_SERVER['REQUEST_URI'] === $route['path']) {
                    $controller = new $route['controller']();
                    $request    = new Request($route['path']);
                    $response   = new Response();

                    $controller->{$route['handler']}($request, $response);
                }
            }
        }
    }
}
