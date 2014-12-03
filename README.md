Web Socket Chat
===============
Online chat based on web sockets and ratchet php

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

1. To start chat server need to create console command and setup it as demon:
    
    - Create controller which extends `yii\console\controller`:
        
        ServerController extends \yii\console\controller
        
    - Create action to start server:
    
        public function actionRun()
        {
            $server = IoServer::factory(new HttpServer(new WsServer(new Chat(new ChatManager()))), 8080);
            $server->run();
        }
        
    If you want to use chat for auth users, you must to specify `userClassName` property for `ChatManager` instance.
    For example:
    
        
        $manager = Yii::configure(new ChatManager(), [
            'userClassName' => '\yii\db\ActiveRecord' //allow to get users from MySQL or PostgreSQL
        ]);
        
    - Now, you can run chat server with `yii` console command:
    
        yii server/run
        
2. To add chat on page just call:


    <?=ChatWidget::widget();?>
    
    
or if you want to use chat for auth users just add config as parameter:
    
    
    <?=ChatWidget::widget([
        'auth' => true,
        'user_id' => '' // setup id of current logged user
    ]);?>
    
List of available options:
    
    
    auth - default false
    user_id - default null
    port - default 8080
