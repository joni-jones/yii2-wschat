<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use WSChat\ChatManager;
use WSChat\Chat;
require dirname(__DIR__).'/vendor/autoload.php';

$server = IoServer::factory(new HttpServer(new WsServer(new Chat(new ChatManager()))), 8080);
$server->run();