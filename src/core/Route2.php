<?php
// namespace Core;
// use Controller\ErrorController;

// class Route2
// {
//     private $routes = [];

//     public function get($route, $controllerAction)
//     {
//         $this->routes['GET'][$route] = $controllerAction;

//     }

//     public function post($route, $controllerAction)
//     {
//         $this->routes['POST'][$route] = $controllerAction;
//     }

//     public static function handleRequest()
//     {
//         $requestUri = $_SERVER['REQUEST_URI'];
//         $requestMethod = $_SERVER['REQUEST_METHOD'];

//         // Nettoyer et préparer l'URI
//         $requestUri = explode('?', $requestUri)[0]; // Enlever les paramètres de requête
//         $requestUri = rtrim($requestUri, '/');

//         $controllerName = null;
//         $methodName = null;
//         $params = [];
//         $foundRoute = false;

//         foreach ($this->routes[$requestMethod] as $route => $routeConfig) {
//             $routePattern = preg_replace('/#([a-zA-Z0-9_-]+)/', '([^/]+)', $route);
           
//             $routePattern = '#^' . $routePattern . '$#';

//             if (preg_match($routePattern, $requestUri, $matches)) {
//                // var_dump(  array_shift($matches));//dette/add
//                 array_shift($matches); 
//                 echo ("<br/>");
             

//                 $controllerName = "Controller\\" . $routeConfig['controller'];
//                 $methodName = $routeConfig['method'];
//                 $params = $matches;
//                 $foundRoute = true;
//                 break;
//             }
//         }

//         if (!$foundRoute) {
//             // Route non trouvée, rediriger vers une page d'erreur 404
//             $errorController = new ErrorController();
//             $errorController->error404();
//             return;
//         }
        
//         var_dump(''. $controllerName .''. $methodName .'');
//         die();

//         // Utiliser la réflexion pour vérifier l'existence de la classe et de la méthode
//         $reflectionClass = null;
//         try {
//             $reflectionClass = new \ReflectionClass($controllerName);
//         } catch (\ReflectionException $e) {
//             http_response_code(404);
//             echo "Controller not found";
//             return;
//         }

//         if ($reflectionClass->isInstantiable()) {
//             // Vérifier si la méthode existe et est publique dans le contrôleur
//             if ($reflectionClass->hasMethod($methodName)) {
//                 $reflectionMethod = $reflectionClass->getMethod($methodName);

//                 if ($reflectionMethod->isPublic()) {
//                     $controllerInstance = $reflectionClass->newInstance();

//                     // Appeler la méthode du contrôleur avec les paramètres
//                     $reflectionMethod->invokeArgs($controllerInstance, $params);
//                 } else {
//                     http_response_code(404);
//                     echo "Method not found or not accessible";
//                 }
//             } else {
//                 http_response_code(404);
//                 echo "Method not found";
//             }
//         } else {
//             http_response_code(404);
//             echo "Controller not instantiable";
//         }
//     }
// }
// class Route
// {
//     private static $routes = [];

//     public static function get($route, $controllerAction)
//     {
//         self::$routes['GET'][$route] = $controllerAction;
//     }

//     public static function post($route, $controllerAction)
//     {
//         self::$routes['POST'][$route] = $controllerAction;
//     }

//     public static function handleRequest()
//     {
//         $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//         $requestMethod = $_SERVER['REQUEST_METHOD'];
//         var_dump($requestMethod);
//         if (isset(self::$routes[$requestMethod][$requestUri])) {
//             $controllerAction = explode('@', self::$routes[$requestMethod][$requestUri]);
//             $controller = "Controller\\" . $controllerAction[0];
//             $action = $controllerAction[1];

//             $controllerInstance = new $controller();
//             $controllerInstance->$action();
//         } else {
//             http_response_code(404);
//             echo "Page not found";
//         }
//     }
// }