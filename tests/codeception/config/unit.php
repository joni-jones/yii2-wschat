<?php
return [
    'id' => 'wschat-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en', //base language
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ]
    ],
];
