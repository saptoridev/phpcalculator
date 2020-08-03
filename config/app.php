<?php

return [
    'providers' => [
        \Jakmall\Recruitment\Calculator\History\CommandHistoryServiceProvider::class,
    ],
    'connections' => [
        'postgresql' => [
            'driver' => 'pgsql',           
            'host' =>  'localhost',
            'port' => '5432',
            'database' => 'phpcalculator',
            'username' => 'dev',
            'password' => '123456',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]
    ],
];
