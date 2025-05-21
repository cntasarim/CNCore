<?php
namespace App\Core;

/**
 * Router Class
 * 
 * Handles URL routing with named routes and middleware
 */
class Router
{
    private static $routes = [];
    private static $namedRoutes = [];
    private static $globalMiddleware = [];
    
    /**
     * Add a route
     * 
     * @param string $method HTTP method
     * @param string $uri URI to match
     * @param string $controller Controller@action string
     * @param string|null $name Route name
     * @param array $middleware Middleware to apply
     */
    private static function addRoute($method, $uri, $controller, $name = null, $middleware = [])
    {
        // Create route array
        $route = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'middleware' => $middleware
        ];
        
        // Add to routes array
        self::$routes[] = $route;
        
        // Add to named routes if name provided
        if ($name !== null) {
            self::$namedRoutes[$name] = $uri;
        }
    }
    
    /**
     * Add a GET route
     * 
     * @param string $uri URI to match
     * @param string $controller Controller@action string
     * @param string|null $name Route name
     * @param array $middleware Middleware to apply
     */
    public static function get($uri, $controller, $name = null, $middleware = [])
    {
        self::addRoute('GET', $uri, $controller, $name, $middleware);
    }
    
    /**
     * Add a POST route
     * 
     * @param string $uri URI to match
     * @param string $controller Controller@action string
     * @param string|null $name Route name
     * @param array $middleware Middleware to apply
     */
    public static function post($uri, $controller, $name = null, $middleware = [])
    {
        self::addRoute('POST', $uri, $controller, $name, $middleware);
    }
    
    /**
     * Add a PUT route
     * 
     * @param string $uri URI to match
     * @param string $controller Controller@action string
     * @param string|null $name Route name
     * @param array $middleware Middleware to apply
     */
    public static function put($uri, $controller, $name = null, $middleware = [])
    {
        self::addRoute('PUT', $uri, $controller, $name, $middleware);
    }
    
    /**
     * Add a DELETE route
     * 
     * @param string $uri URI to match
     * @param string $controller Controller@action string
     * @param string|null $name Route name
     * @param array $middleware Middleware to apply
     */
    public static function delete($uri, $controller, $name = null, $middleware = [])
    {
        self::addRoute('DELETE', $uri, $controller, $name, $middleware);
    }
    
    /**
     * Add a global middleware
     * 
     * @param Middleware $middleware Middleware instance
     */
    public static function useMiddleware($middleware)
    {
        self::$globalMiddleware[] = $middleware;
    }
    
    /**
     * Check if a named route exists
     * 
     * @param string $name Route name
     * @return bool True if route exists, false otherwise
     */
    public static function routeExists($name)
    {
        return isset(self::$namedRoutes[$name]);
    }
    
    /**
     * Generate URL for a named route
     * 
     * @param string $name Route name
     * @param array $params Route parameters
     * @return string Generated URL
     * @throws \Exception If route doesn't exist
     */
    public static function generateUrl($name, $params = [])
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new \Exception("Route '{$name}' not found");
        }
        
        $uri = self::$namedRoutes[$name];
        
        // Replace route parameters
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $uri = str_replace("{{$param}}", $value, $uri);
            }
        }
        
        return $uri;
    }
    
    /**
     * Redirect to a named route
     * 
     * @param string $name Route name
     * @param array $params Route parameters
     */
    public static function redirectToRoute($name, $params = [])
    {
        $url = self::generateUrl($name, $params);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Dispatch a route
     * 
     * @param string $uri URI to match
     * @throws \Exception If route not found
     */
    public static function dispatch($uri)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Handle PUT, DELETE methods via POST with _method field
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        // Find matching route
        foreach (self::$routes as $route) {
            if (self::matchRoute($uri, $route['uri']) && $route['method'] === $method) {
                return self::executeRoute($route);
            }
        }
        
        // No route found
        ErrorHandler::displayErrorPage(404);
        exit;
    }
    
    /**
     * Match a route URI
     * 
     * @param string $uri Current URI
     * @param string $routeUri Route URI pattern
     * @return bool True if matched, false otherwise
     */
    private static function matchRoute($uri, $routeUri)
    {
        // Convert route parameters to regex pattern
        $pattern = preg_replace('/\/{([^}]+)}/', '/([^/]+)', $routeUri);
        $pattern = "@^" . $pattern . "$@D";
        
        return preg_match($pattern, $uri);
    }
    
    /**
     * Execute a route
     * 
     * @param array $route Route data
     */
    private static function executeRoute($route)
    {
        // Extract controller and action
        list($controllerName, $action) = explode('@', $route['controller']);
        
        // Add namespace
        $controllerClass = "App\\Controllers\\{$controllerName}";
        
        // Check if controller exists
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller '{$controllerClass}' not found");
        }
        
        // Create controller instance
        $controller = new $controllerClass();
        
        // Check if action exists
        if (!method_exists($controller, $action)) {
            throw new \Exception("Action '{$action}' not found in controller");
        }
        
        // Combine global and route middleware
        $middleware = array_merge(self::$globalMiddleware, $route['middleware']);
        
        // Execute middleware pipeline
        self::executeMiddleware($middleware, function () use ($controller, $action) {
            // Call controller action
            $controller->$action();
        });
    }
    
    /**
     * Execute middleware pipeline
     * 
     * @param array $middleware Middleware instances
     * @param callable $target Target function to execute
     * @param int $index Current middleware index
     */
    private static function executeMiddleware($middleware, $target, $index = 0)
    {
        if ($index >= count($middleware)) {
            // End of middleware chain, execute target
            $target();
            return;
        }
        
        // Get current middleware
        $currentMiddleware = $middleware[$index];
        
        // Create next function that calls the next middleware
        $next = function () use ($middleware, $target, $index) {
            self::executeMiddleware($middleware, $target, $index + 1);
        };
        
        // Execute current middleware
        $currentMiddleware->handle($next);
    }
}