Web Socket Chat
===============

Online chat based on web sockets and ratchet php

[![Join the chat at https://gitter.im/joni-jones/yii2-wschat](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/joni-jones/yii2-wschat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist joni-jones/yii2-wschat "*"
```

or add

```
"joni-jones/yii2-wschat": "*"
```

to the require section of your `composer.json` file.

Usage
------------

1. Chat use [MongoDB](http://docs.mongodb.org/) and [yii2-mongodb](http://www.yiiframework.com/doc-2.0/ext-mongodb-index.html)
extension to store messages history, so you need just specify connection in `console` config:

    ```php
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://username:password@localhost:27017/dbname'
        ]
    ]
    ```
    In created mongodb database you need to create collection named as `history`;

2. To start chat server need to create console command and setup it as demon:
    
    - Create controller which extends `yii\console\controller`:
        
        ```php
        ServerController extends \yii\console\controller
        ```
        
    - Create action to start server:
    
        ```php
        public function actionRun()
        {
            $server = IoServer::factory(new HttpServer(new WsServer(new Chat(new ChatManager()))), 8080);
            $server->run();
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
    <?=ChatWidget::widget();?>
    ```
    
    or if you want to use chat for auth users just add config as parameter:
      
        <?=ChatWidget::widget([
            'auth' => true,
            'user_id' => '' // setup id of current logged user
        ]);?>
    
    
        List of available options:
            auth - boolean, default: false
            user_id - mixed, default: null
            port - integer, default: 8080
            chatList - array (allow to set list of preloaded chats), default: [
                id => 1,
                title => 'All'
            ]

You can also store added chat, just specify js callback for vent events:

    Chat.vent('chat:add', function(chatModel) {
        console.log(chatModel);
    });
    
In the callback you will get access to ``Chat.Models.ChatRoom`` backbone model.

> If `YII_DEBUG` is enabled - all js scripts will be loaded separately.

Also by default chat will try to load two images:
`/img/avatar_16.png` and `/img/avatar_32.png`

License
----

MIT
