Web Socket Chat
===============

Online chat based on web sockets and ratchet php

[![Latest Stable Version](https://poser.pugx.org/joni-jones/yii2-wschat/v/stable)](https://packagist.org/packages/joni-jones/yii2-wschat)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/joni-jones/yii2-wschat/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/joni-jones/yii2-wschat/?branch=master)
[![Total Downloads](https://poser.pugx.org/joni-jones/yii2-wschat/downloads)](https://packagist.org/packages/joni-jones/yii2-wschat)
[![License](https://poser.pugx.org/joni-jones/yii2-wschat/license)](https://packagist.org/packages/joni-jones/yii2-wschat)
[![Join the chat at https://gitter.im/joni-jones/yii2-wschat](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/joni-jones/yii2-wschat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

![Demo] (doc/demo.gif)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist joni-jones/yii2-wschat
```

or add

```
"joni-jones/yii2-wschat": "*"
```

to the require section of your `composer.json` file.

Usage
------------

1. The chat extension can use any database storage supported by yii.

    If `mongodb` extension specified the chat will be try to use it as message history storage, otherwise extension
will be use specified in application config db component.

    The simple example how to use mongodb storage is listed below.
Install [MongoDB](http://docs.mongodb.org/) and [yii2-mongodb](http://www.yiiframework.com/doc-2.0/ext-mongodb-index.html)
extension to store messages history and you need just specify connection in `console` config:

    ```php
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://username:password@localhost:27017/dbname'
        ]
    ]
    ```
    In created mongodb database you need to create collection named as `history`;

    IMPORTANT: if you use db component - you need to create table `history` in your database.
The simple examples postgresql and mysql you can see in `tests/codeception` directory.


2. To start chat server need to create console command and setup it as demon:
    - Create controller which extends `yii\console\Controller`:
        
        ```php
        ServerController extends \yii\console\Controller
        ```
        
    - Create action to start server:
    
        ```php
        namespace app\commands;

        use jones\wschat\components\Chat;
        use jones\wschat\components\ChatManager;
        use Ratchet\Server\IoServer;
        use Ratchet\Http\HttpServer;
        use Ratchet\WebSocket\WsServer;
        
        class ServerController extends \yii\console\Controller
        {
            public function actionRun()
            {
                $server = IoServer::factory(new HttpServer(new WsServer(new Chat(new ChatManager()))), 8080);
                $server->run();
                echo 'Server was started successfully. Setup logging to get more details.'.PHP_EOL;
            }
        }
        ```
        
    If you want to use chat for auth users, you must to specify `userClassName` property for `ChatManager` instance.
    For example:
    
    ```php
        $manager = Yii::configure(new ChatManager(), [
            'userClassName' => '\yii\db\ActiveRecord' //allow to get users from MySQL or PostgreSQL
        ]);
    ```
        
    - Now, you can run chat server with `yii` console command:
    
        ```php
        yii server/run
        ```
        
3. To add chat on page just call:

    ```php
    <?php echo ChatWidget::widget();?>
    ```
    
    or if you want to use chat for auth users just add config as parameter:
        
    ```php  
    <?php echo ChatWidget::widget([
        'auth' => true,
        'user_id' => '' // setup id of current logged user
    ]);?>
    ```
    
        List of available options:
        auth - boolean, default: false
        user_id - mixed, default: null
        port - integer, default: 8080
        chatList - array (allow to set list of preloaded chats), default: [
            id => 1,
            title => 'All'
        ],
        add_room - boolean, default: true (allow to user create new chat rooms)

You can also store added chat, just specify js callback for vent events:

    Chat.vent('chat:add', function(chatModel) {
        console.log(chatModel);
    });
    
This code snipped may be added in your code, but after chat widget loading. In the callback you will get access to ``Chat.Models.ChatRoom`` backbone model. Now, you need add your code to save chat room instead `console.log()`.

> If `YII_DEBUG` is enabled - all js scripts will be loaded separately.

Also by default chat will try to load two images:
`/avatar_16.png` and `/avatar_32.png` from assets folder.

Possible issues
----

If you don't see any messages in console log, check `flushInterval` and `exportInterval` of your log configuration component. The simple configuration may looks like this:
```php
'log' => [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'flushInterval' => 1,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info'],
            'logVars' => [],
            'exportInterval' => 1
        ],
    ],
],
```

If you use `https` protocol chat will try to connect to `wss` instead `ws`. But Ratchet PHP [does not support](https://github.com/reactphp/react/issues/2) work via SSL, so
you need to use some proxy like [stunnel](https://www.stunnel.org/index.html).

License
----

MIT
