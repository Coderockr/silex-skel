<?php

putenv('APPLICATION_ENV=development');

$app = require_once __DIR__.'/bootstrao.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;
use Doctrine\DBAL\Migrations\Tools\Console\Command;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application as SymfonyApplication;

$helpers = array('dialog' => new DialogHelper());
if (isset($app['orm.em'])) {
    $helpers['em'] = new EntityManagerHelper($app['orm.em']);
}

$helperSet = new HelperSet($helpers);
$commands = array(
    new Command\DiffCommand(),
    new Command\ExecuteCommand(),
    new Command\GenerateCommand(),
    new Command\MigrateCommand(),
    new Command\StatusCommand(),
    new Command\VersionCommand(),
);

$application = new SymfonyApplication();
$config = new Configuration($app['db']);
$config->setName('Skel Migration');
$config->setMigrationsDirectory(__DIR__.'/data/migrations');
$config->setMigrationsNamespace('Skel\\Migration');
$config->setMigrationsTableName('migration_version');

foreach ($commands as $command) {
    if ($command instanceof Command\AbstractCommand){
        $command->setMigrationConfiguration($config);
    }
    $application->add($command);
}

ConsoleRunner::run($helperSet, $application->all());
