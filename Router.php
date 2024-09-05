<?php 
class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get($url, $handler) {
        $this->routes['GET'][rtrim($url, '/')] = $handler;
    }

    public function post($url, $handler) {
        $this->routes['POST'][rtrim($url, '/')] = $handler;
    }

    public function put($url, $handler) {
        $this->routes['PUT'][rtrim($url, '/')] = $handler;
    }

    public function delete($url, $handler) {
        $this->routes['DELETE'][rtrim($url, '/')] = $handler;
    }

    public function run() {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        $uriParts = explode('?', $uri, 2);
        $path = rtrim($uriParts[0], '/');

        if ($this->basePath) {
            $path = str_replace($this->basePath, '', $path);
        }

        if (isset($this->routes[$httpMethod][$path])) {
            call_user_func($this->routes[$httpMethod][$path]);
        } else {
            http_response_code(404);
            require '404.php';
        }
    }
}