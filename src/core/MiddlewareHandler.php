<?php
namespace Core;


class MiddlewareHandler
{
    public function handle($request, $next, $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            if (method_exists($this, $middleware)) {
                $request = $this->$middleware($request);
            }
        }

        return $next($request);
    }

    public function auth($request)
    {
        // Perform authentication check
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            echo "Access denied";
            exit; // Stop further processing
        }

        return $request;
    }

    public function log($request)
    {
        // Log request details
        error_log("Request URI: " . $request['uri']);
        return $request;
    }

    // Add more middleware methods here
}
