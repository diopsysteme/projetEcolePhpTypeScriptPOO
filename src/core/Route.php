<?php

namespace Core;

use App\App;

class Route
{
    private static $routes = [];
    private static $middlewareGroups = [];
    
    private $route;
    private $method;
    private $controllerAction;
    private $middleware = [];

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function get($route, $controllerAction)
    {
        $container = App::getInstance()->getContainer();
        $instance = new self($container);
        $instance->route = $route;
        $instance->method = 'GET';
        $instance->controllerAction = $controllerAction;

        self::$routes['GET'][$route] = [
            'controllerAction' => $controllerAction,
            'middleware' => []
        ];

        return $instance;
    }

    public static function post($route, $controllerAction)
    {
        $container = App::getInstance()->getContainer();
        $instance = new self($container);
        $instance->route = $route;
        $instance->method = 'POST';
        $instance->controllerAction = $controllerAction;

        self::$routes['POST'][$route] = [
            'controllerAction' => $controllerAction,
            'middleware' => []
        ];

        return $instance;
    }

    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            $this->middleware = array_merge($this->middleware, $middleware);
        } else {
            $this->middleware[] = $middleware;
        }

        self::$routes[$this->method][$this->route]['middleware'] = $this->middleware;

        return $this;
    }

    public static function middlewareGroup($group, $middleware)
    {
        self::$middlewareGroups[$group] = $middleware;
    }

    public static function handleRequest()
    {
        $container = App::getInstance()->getContainer();
        $requestUri = self::cleanRequestUri($_SERVER['REQUEST_URI']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        list($controllerAction, $middleware, $params) = self::matchRoute($requestUri, $requestMethod);

        if ($controllerAction === null) {
            self::handleNotFound();
            return;
        }

        $request = [
            'uri' => $requestUri,
            'method' => $requestMethod,
            'params' => $params
        ];

        $next = function($request) use ($controllerAction, $container) {
            $controllerFactory = $container->get('controller_factory');
            $controllerInstance = $controllerFactory("Controller\\" . $controllerAction['controller']);
            self::invokeControllerMethod($controllerInstance, $controllerAction['method'], $request['params']);
        };
        $controllerFactory = $container->get('controller_factory');
        $middlewareHandler = $controllerFactory("Core\\MiddlewareHandler");
        $middlewareHandler->handle($request, $next, $middleware);
    }

    protected static function cleanRequestUri($requestUri)
    {
        $requestUri = explode('?', $requestUri)[0];
        return rtrim($requestUri, '/');
    }

    protected static function matchRoute($requestUri, $requestMethod)
    {
        foreach (self::$routes[$requestMethod] as $route => $routeConfig) {
            $routePattern = preg_replace('/#([a-zA-Z0-9_-]+)/', '([^/]+)', $route);
            $routePattern = '#^' . $routePattern . '$#';

            if (preg_match($routePattern, $requestUri, $matches)) {
                array_shift($matches);
                $controllerAction = $routeConfig['controllerAction'];
                $middleware = self::resolveMiddleware($routeConfig['middleware']);
                return [$controllerAction, $middleware, $matches];
            }
        }

        return [null, [], []];
    }

    protected static function resolveMiddleware($middleware)
    {
        $resolvedMiddleware = [];

        foreach ($middleware as $item) {
            if (isset(self::$middlewareGroups[$item])) {
                $resolvedMiddleware = array_merge($resolvedMiddleware, self::$middlewareGroups[$item]);
            } else {
                $resolvedMiddleware[] = $item;
            }
        }

        return $resolvedMiddleware;
    }

    protected static function handleNotFound()
    {
        http_response_code(404);
        echo "404 Not Found";
    }

    protected static function invokeControllerMethod($controllerInstance, $methodName, $params)
    {
        $reflectionClass = new \ReflectionClass($controllerInstance);

        if (!$reflectionClass->hasMethod($methodName) || !$reflectionClass->getMethod($methodName)->isPublic()) {
            http_response_code(404);
            echo "Method not found or not accessible";
            return;
        }

        $reflectionClass->getMethod($methodName)->invokeArgs($controllerInstance, $params);
    }
}
