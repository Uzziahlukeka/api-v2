<?php
namespace uzziah;
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

$router = new Router();

$router->post('/user/create', function() {
     require 'api/user/create.php';
 });
$router->delete('/user/delete', function() {
    require 'api/user/delete.php';
});
$router->post('/user/edit', function() {
    require 'api/user/update.php';
});
$router->get('/user/read', function() {
    require 'api/user/read_single.php';
});
$router->get('/user/forget', function() {
    require 'api/user/forget.php';
});
$router->get('/user/findbytoken', function() {
    require 'api/user/findbytoken.php';
});
$router->post('/user/newpassword', function() {
    require 'api/user/setResetToken.php';
});

$router->post('/login', function() {
    require 'api/user/login.php';
});

$router->post('/add/item', function() {
    require 'api/items/create.php';
});
$router->post('/bid/item', function() {
    require 'api/bids/bid.php';
});
$router->get('/bid/read', function() {
    require 'api/bids/read.php';
});
$router->get('/paymentData/item', function() {
    require 'api/bids/bidData.php';
});
$router->delete('/delete/item', function() {
    require 'api/items/delete.php';
});
$router->post('/edit/item', function() {
    require 'api/items/update.php';
});
$router->get('/app/read/items', function() {
    require 'api/items/read.php';
});
$router->get('/read/item', function() {
    require 'api/items/read_single.php';
});

$router->get('/home', function() {
    require 'index.php';
});

$router->get('/', function() {
    require 'home.php';
});


$router->run();

