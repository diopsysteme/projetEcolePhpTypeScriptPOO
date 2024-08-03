<?php

namespace Core;


class Route
{
    private static  $sessionClass;
    private static $fileClass;
    private static $databaseClass;
    private static $routeClass;
    private static $modelClass;
    private static $controllerClass;
    private static $validatorClass;
    
    public function __construct($config)
    {
        $this->sessionClass = $config['application']['session'];
        $this->fileClass = $config['application']['file'];
        $this->databaseClass = $config['application']['database'];
        $this->routeClass = $config['application']['route'];
        $this->modelClass = $config['application']['model'];
        $this->controllerClass = $config['application']['controller'];
    }

    private static $routes = [];

    public static function get($route, $controllerAction)
    {
        self::$routes['GET'][$route] = $controllerAction;
    }

    public static function post($route, $controllerAction)
    {
        self::$routes['POST'][$route] = $controllerAction;
    }

    public static function handleRequest($config)
    {
        self::$sessionClass = $config['application']['session'];
        self::$fileClass = $config['application']['file'];
        self::$databaseClass = $config['application']['database'];
        self::$routeClass = $config['application']['route'];
        self::$modelClass = $config['application']['model'];
        self::$controllerClass = $config['application']['controller'];
        self::$validatorClass = $config['application']['validator'];
        $requestUri = self::cleanRequestUri($_SERVER['REQUEST_URI']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        list($controllerName, $methodName, $params) = self::matchRoute($requestUri, $requestMethod);

        if ($controllerName === null || $methodName === null) {
            self::handleNotFound();
            return;
        }

        self::invokeControllerMethod($controllerName, $methodName, $params);
    }

    protected static function cleanRequestUri($requestUri)
    {
        $requestUri = explode('?', $requestUri)[0]; // Enlever les paramètres de requête
        return rtrim($requestUri, '/');
    }

    protected static function matchRoute($requestUri, $requestMethod)
    {
        foreach (self::$routes[$requestMethod] as $route => $routeConfig) {
            $routePattern = preg_replace('/#([a-zA-Z0-9_-]+)/', '([^/]+)', $route);
            $routePattern = '#^' . $routePattern . '$#';

            if (preg_match($routePattern, $requestUri, $matches)) {
                array_shift($matches); // Enlever le match complet
                $controllerName = "Controller\\" . $routeConfig['controller'];
                $methodName = $routeConfig['method'];
                return [$controllerName, $methodName, $matches];
            }
        }

        return [null, null, []];
    }

    protected static function handleNotFound()
    {
        $session=Factory::instantiateClass(self::$sessionClass);
        $validator=Factory::instantiateClass(self::$validatorClass);
        // var_dump($session);
        $file=Factory::instantiateClass(self::$fileClass);
        $errorController = new ErrorController($session, $validator,$file);
        
    }

    protected static function invokeControllerMethod($controllerName, $methodName, $params)
    {
        $reflectionClass = self::getReflectionClass($controllerName);

        if ($reflectionClass === null || !$reflectionClass->isInstantiable()) {
            http_response_code(404);
            echo "Controller not found or not instantiable";
            return;
        }

        if (!$reflectionClass->hasMethod($methodName) || !$reflectionClass->getMethod($methodName)->isPublic()) {
            http_response_code(404);
            echo "Method not found or not accessible";
            return;
        }

        
        $session=Factory::instantiateClass(self::$sessionClass);
        $file=Factory::instantiateClass(self::$fileClass);
        $validator=Factory::instantiateClass(self::$validatorClass);
        // var_dump($session);
        $controllerInstance = $reflectionClass->newInstance($session,$validator,$file);
        // var_dump($controllerInstance);
        $reflectionClass->getMethod($methodName)->invokeArgs($controllerInstance, $params);
    }

    protected static function getReflectionClass($controllerName)
    {
        try {
            return new \ReflectionClass($controllerName);
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}