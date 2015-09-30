<?php

putenv('APPLICATION_ENV=development');

$app = require_once __DIR__.'/bootstrao.php';

use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;

$application = new SymfonyApplication();
$helpers['dialog'] = new DialogHelper();
$helpers['em'] = new EntityManagerHelper($app['orm.em']);

$helperSet = new HelperSet($helpers);

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet, $application->all());
