#!/usr/bin/env php
<?php

use Illuminate\Console\Application;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    require_once __DIR__.'/vendor/autoload.php';
    $appConfig = require_once __DIR__.'/config/app.php';

    $dbconf = $appConfig['connections']['postgresql'];
    $capsule = new Capsule;
    $capsule->addConnection([
     'driver' => $dbconf['driver'],
     'host' => $dbconf['host'],
     'port' => $dbconf['port'],
     'database' => $dbconf['database'],
     'username' => $dbconf['username'],
     'password' => $dbconf['password'],
     'charset' => 'utf8',
     'collation' => 'utf8_unicode_ci',
     'prefix' => '',
    ]);
    // Setup the Eloquent ORM
    $capsule->bootEloquent();

    $container = new Container();
    $dispatcher = new Dispatcher();
    $app = new Application($container, $dispatcher, '0.6');
    $app->setName('Calculator');
   
    $providers = $appConfig['providers'];

    foreach ($providers as $provider) {
        $container->make($provider)->register($container);
    }

    $commands = require_once __DIR__.'/commands.php';
    $commands = collect($commands)
        ->map(
            function ($command) use ($app) {
                return $app->getLaravel()->make($command);
            }
        )
        ->all()
    ;

    $app->addCommands($commands);

    $app->run(new ArgvInput(), new ConsoleOutput());
} catch(RuntimeException $e){   
    printf("%s\n",$e->getMessage());
}catch (Throwable $e) {
    print_r($e);
}
