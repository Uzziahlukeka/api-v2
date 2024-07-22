<?php
// token.php
namespace uzziah;
require 'vendor/autoload.php';
require 'oauth_config.php';

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
//use zend\Diactoros\ServerRequestFactory;

$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = new Response();

try {
    $response = $server->respondToAccessTokenRequest($request, $response);
} catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
    $response = $exception->generateHttpResponse($response);
} catch (\Exception $exception) {
    $response->getBody()->write($exception->getMessage());
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();
