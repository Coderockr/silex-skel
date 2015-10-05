<?php

$loader = require __DIR__.'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use DerAlex\Silex\YamlConfigServiceProvider;
use MJanssen\Provider\RoutingServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

$app = new Application();

$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'homolog';
$app['debug'] = ($env !== 'production') ? true : false;

//configuration
$app->register(new YamlConfigServiceProvider(__DIR__.'/config/config.yml'));
(new RoutingServiceProvider())->addRoutes($app, $app['config']['routes']);
$app->register(new MonologServiceProvider(), $app['config']['monolog'][$env]);
$app->register(new TwigServiceProvider(), $app['config']['twig']);
$app->register(new DoctrineServiceProvider, $app['config']['doctrine'][$env]['options']);
$app->register(new DoctrineOrmServiceProvider(), $app['config']['doctrine'][$env]['orm']);

return $app;
