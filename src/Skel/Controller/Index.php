<?php

namespace Skel\Controller;

use Silex\Application;

class Index
{
    public function index(Application $app)
    {
        return $app['twig']->render('index/index.twig');
    }
}
