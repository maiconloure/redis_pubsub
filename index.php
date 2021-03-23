<?php

require 'vendor/autoload.php';

$remoteRedis = [
    'host' => 'redispubsub',
    'port' => 6379,
    'read_write_timeout' => 0
];

$redis = new Predis\Client($remoteRedis);

$loop = $redis->pubSubloop();

$loop->subscribe("canal_exemplo");

foreach ($loop as $message) {
    switch ($message->kind) {
        case 'subscribe':
            echo "Acabei de me inscrever no canal: {$message->channel}\n";
            break;
        case 'message';
            echo "Mensagem recebida do canal {$message->channel}: {$message->payload}\n";
            if($message->payload == 'sair') {
                echo "saindo do canal {$message->channel}\n";
                $loop->unsubscribe();
            }
            break;
    }
}

unset($loop);