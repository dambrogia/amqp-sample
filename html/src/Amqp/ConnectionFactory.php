<?php

namespace App\Amqp;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConnectionFactory
{
    /**
     * https://www.rabbitmq.com/tutorials/tutorial-one-php.html
     * @return AMQPStreamConnection
     */
    public static function getConnection(): AMQPStreamConnection
    {
        // These are hardcoded into the docker-compose.yml for the amqp_rmq
        // services env vars
        return new AMQPStreamConnection('amqp_broker', 5672, 'docker', 'docker');
    }
}
