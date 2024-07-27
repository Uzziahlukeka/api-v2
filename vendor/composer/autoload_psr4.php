<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'uzziah\\' => array($baseDir . '/config', $baseDir . '/models'),
    'Zend\\Diactoros\\' => array($vendorDir . '/zendframework/zend-diactoros/src'),
    'StellaMaris\\Clock\\' => array($vendorDir . '/stella-maris/clock/src'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Psr\\Clock\\' => array($vendorDir . '/psr/clock/src'),
    'League\\Uri\\' => array($vendorDir . '/league/uri/src', $vendorDir . '/league/uri-interfaces/src'),
    'League\\OAuth2\\Server\\' => array($vendorDir . '/league/oauth2-server/src'),
    'League\\Event\\' => array($vendorDir . '/league/event/src'),
    'Lcobucci\\JWT\\' => array($vendorDir . '/lcobucci/jwt/src'),
    'Lcobucci\\Clock\\' => array($vendorDir . '/lcobucci/clock/src'),
    'Defuse\\Crypto\\' => array($vendorDir . '/defuse/php-encryption/src'),
    'Acer\\ApiV2\\' => array($baseDir . '/src'),
);