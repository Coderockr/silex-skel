<?php

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    putenv('APPLICATION_ENV=development');
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use MJanssen\Provider\RoutingServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'homolog';

$app = new Application();
$app['debug'] = ($env !== 'production') ? true : false;

//configuration
$app->register(new YamlConfigServiceProvider(__DIR__.'/config/config.yml'));
$routingServiceProvider = new RoutingServiceProvider();
$routingServiceProvider->addRoutes($app, $app['config']['routes']);
$app->register(new Silex\Provider\MonologServiceProvider(), $app['config']['monolog'][$env]);
$app->register(new Silex\Provider\TwigServiceProvider(), $app['config']['twig']);
$app->register(new DoctrineServiceProvider, $app['config']['doctrine'][$env]['options']);

$app->register(new DoctrineOrmServiceProvider(), $app['config']['doctrine'][$env]['orm']);

return $app;
