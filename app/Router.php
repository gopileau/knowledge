<?php

namespace App;

class Router {
    private $routes = [];
    private $middlewares = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middlewares' => []
        ];
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function addRouteWithMiddleware($method, $path, $handler, $middlewares = []) {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function handle($request) {
        $method = $request['REQUEST_METHOD'];
        $path = parse_url($request['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            return 'Page non trouvée';
        }

        foreach ($this->routes[$method] as $route => $routeData) {
            $pattern = "@^" . $route . "$@";
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match

                // Execute middlewares
                foreach ($routeData['middlewares'] as $middleware) {
                    if (is_callable($middleware)) {
                        $result = call_user_func($middleware, $request);
                        if ($result === false) {
                            // Middleware blocked the request
                            http_response_code(403);
                            echo 'Access denied by middleware';
                            exit;
                        }
                    }
                }

                $handler = $routeData['handler'];
                if (is_array($handler) && count($handler) === 2) {
                    $className = $handler[0];
                    $methodName = $handler[1];
                    $instance = new $className();
                    return call_user_func_array([$instance, $methodName], $matches);
                } elseif (is_callable($handler)) {
                    return call_user_func($handler, ...$matches);
                }
                return $handler();
            }
        }

        http_response_code(404);
        return 'Page non trouvée';
    }
}
