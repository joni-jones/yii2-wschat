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
        
    - Now, you can run chat server with `yii` console command:
    
        yii server/run
        
2. To add chat on page just call:

```
<?=ChatWidget::widget();?>
```
