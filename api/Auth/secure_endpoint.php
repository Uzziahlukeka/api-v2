<?php
// secure_endpoint.php

namespace uzziah;
require 'vendor/autoload.php';
require 'oauth_config.php';

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\ResourceServer;
use Zend\Diactoros\ServerRequest as ServerRequest;

//use Zend\Diactoros\ServerRequestFactory;

$resourceServer = new ResourceServer(
    $accessTokenRepository,
    new CryptKey('C:\private.pub')
);

$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = new Response();

try {
    $request = $resourceServer->validateAuthenticatedRequest($request);
    // Process your request here
    $response->getBody()->write(json_encode(['message' => 'This is a protected resource']));
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
