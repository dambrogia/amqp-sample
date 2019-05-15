# PHP AMQP Example with Rabbit MQ
This is a very basic example of using Rabbit MQ with PHP. The symfony console component is used as well.

### Requirements
- Docker
- Docker Compose

##### Clone repo

    git clone https://github.com/dambrogia/amqp-sample.git
    cd amqp-sample

#### Start Docker with Docker Compose
_This might take a while..._

    docker-compose up -d

#### Enter Docker container and start consumer

    docker exec -it amqp_consumer /bin/zsh
    /var/www/html/bin/console amqp:consume

#### _In a new terminal window_ Enter Docker container and produce new message(s)

    docker exec -it amqp_producer /bin/zsh
    /var/www/html/bin/console amqp:produce [--id=ID]

After you produce these messages, you should see them appear in your consumer.

##### Sending Multiple Messages at Once:
    for i in 1 2 3 4 5; do
        /var/www/html/bin/console amqp:produce --id=$i;
    done


___
### What's going on?
You have 3 components:
- Producer
  - Sends messages to the Broker
- Broker
  - The AMQP service that accepts messages from the Producer and distrubutes them to the Consumer
- Consumer
  - Consumes messages from the Broker

When you start your consumer, the terminal should remain active -- meaning it should be persistently consuming messages as they come in.

As you produce messages with your Producer the Broker will receive them and distribute them to the Consumer in a FIFO order.

### Code Styles (PSR-2)

    docker exec -it amqp_producer /bin/zsh
    cd /var/www/html/
    ./vendor/.bin/phpcs --standard=PSR2 src bin

### XDebug

Xdebug is enabled by default, if you are _not_ running Docker for Mac and want to utilize XDebug, you will need to update the `REMOTE_HOST` value that's found towards the bottom of `services/web/Dockerfile`.


