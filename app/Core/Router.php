<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $middleware = [];
    
    public function get($path, $callback, $middleware = [])
    {
        $this->addRoute('GET', $path, $callback, $middleware);
    }
    
    public function post($path, $callback, $middleware = [])
    {
        $this->addRoute('POST', $path, $callback, $middleware);
    }
    
    public function put($path, $callback, $middleware = [])
    {
        $this->addRoute('PUT', $path, $callback, $middleware);
    }
    
    public function delete($path, $callback, $middleware = [])
    {
        $this->addRoute('DELETE', $path, $callback, $middleware);
    }
    
    private function addRoute($method, $path, $callback, $middleware)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }
    
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle();
                }
                
                if (is_callable($route['callback'])) {
                    return call_user_func_array($route['callback'], $matches);
                }
                
                if (is_string($route['callback'])) {
                    list($controller, $method) = explode('@', $route['callback']);
                    $controller = "App\\Controllers\\{$controller}";
                    
                    if (class_exists($controller)) {
                        $controllerInstance = new $controller();
                        if (method_exists($controllerInstance, $method)) {
                            return call_user_func_array([$controllerInstance, $method], $matches);
                        }
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
