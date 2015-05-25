<?php
return [
    'id' => 'wschat-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/wschat-tests'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=wschat_tests',
//            'dsn' => 'mysql:host=localhost;dbname=wschat_tests',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
