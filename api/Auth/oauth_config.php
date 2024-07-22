<?php 
// oauth_config.php

namespace uzziah;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

require 'vendor/autoload.php';

$clientRepository = new $clientRepository () ;
//ClientRepository(); // instance of ClientRepositoryInterface
$accessTokenRepository = new $accessTokenRepository ();
// AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
$scopeRepository = new $scopeRepository(); // instance of ScopeRepositoryInterface
$userRepository = new UserRepository(); // instance of UserRepositoryInterface
$refreshTokenRepository = new $refreshTokenRepository () ; // RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

$privateKey = 'file://C:\private.key';
$encryptionKey = 'encryption-key';

$server = new AuthorizationServer(
    $clientRepository,
    $accessTokenRepository,
    $scopeRepository,
    new CryptKey($privateKey),
    $encryptionKey
);

// Enable the Password grant type
$grant = new PasswordGrant(
    $userRepository,
    $refreshTokenRepository
);
$grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire in 1 month

$server->enableGrantType(
    $grant,
    new \DateInterval('PT1H') // access tokens will expire in 1 hour
);
