<?php
require_once __DIR__.'/bootstrap.php';

use Silex\Application,
    Silex\Provider\DoctrineServiceProvider,
    Symfony\Component\HttpFoundation\Request,
    Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

use Symfony\Component\HttpFoundation\Response;
use Coderockr\SOA\RestControllerProvider;
use Coderockr\SOA\RpcControllerProvider;

$app = new Application();

$app['debug'] = true;

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
    
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'username' => 'contato@coderockr.com',
    'password' => 'H&m6&mUE',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
);


$redirectUnlogged = function () use ($app) {
    if ($app['session']->get('email') == null) {
        return $app->redirect('/');
    }
};

$redirectCommonUser = function () use ($redirectUnlogged, $app) {
    if ($app['session']->get('email') && !$app['session']->get('isAdmin')){
        return $app->redirect('/project');
    }
};

$app->error(function (\Exception $e, $code) use($app) {
    switch ($code) {
        case 404:
            $message = $app['twig']->render('error404.twig', array('code'=>$code, 'message' => $e->getMessage()));
            break;
        default:
            $message = $e->getMessage() . ' no arquivo ' . $e->getFile() . ', na linha: '. $e->getLine();
            break;
    }
    return new Response($message, $code);
});
    
$app['sortCreated'] = $app->protect(function ($a, $b) {
    if ($a->getCreated() == $b->getCreated()) {
        return 0;
    }
    return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
});

/**
 * Group controllers by route and adding before behaviour
 */

// /**
//  * Setting the routes for each controller
//  */
// $index = $app['controllers_factory'];
// // Index controller Routes
// $index->get('/', 'Skel\Controller\IndexController::index');
// $app->mount('/', $index);

// foreach (new DirectoryIterator('config/routes') as $fileInfo) {
//     if($fileInfo->isDot()) continue;
//     $route = $fileInfo->getBasename('.' . $fileInfo->getExtension());
//     $file = $fileInfo->getPath().'/'.$fileInfo->getBasename();
//     $app->mount("/$route", include $file);
// }

//getting the EntityManager
$app->register(new DoctrineServiceProvider, array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'skel',
        'password' => 'skel',
        'dbname' => 'skel'
    )
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => '/tmp/' . getenv('APPLICATION_ENV'),
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'Skel\Model',
                'path' => __DIR__ . '/src'
            )
        )
    ),
    'orm.proxies_namespace' => 'EntityProxy',
    'orm.auto_generate_proxies' => true
));

$api = new RestControllerProvider();
$api->setCache($cache); //Doctrine cache, created in bootstrap.php
$api->setEntityNamespace('Skel\Model');
$app->mount('/api', $api);

$rpc = new RpcControllerProvider();
$rpc->setCache($cache); //Doctrine cache, created in bootstrap.php
$rpc->setServiceNamespace('Skel\Service');
$app->mount('/rpc', $rpc);
