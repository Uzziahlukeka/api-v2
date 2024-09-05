<?php

require_once 'Router.php';

$router = new \app\Router();

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
$router->get('/read/items', function() {
    require 'api/items/read.php';
});
$router->get('/read/item', function() {
    require 'api/items/read_single.php';
});

$router->get('/', function() {
    require 'index.php';
});

$router->get('/home', function() {
    require 'home.php';
});


$router->run();

