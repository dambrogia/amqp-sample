<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Amqp\ConnectionFactory;
use App\Amqp\Queue;
use PhpAmqpLib\Message\AMQPMessage;

class Consume extends Command
{
    protected static $defaultName = 'amqp:consume';

    protected function configure()
    {
        $this->setDescription('Consume all items in the queue.')
            ->setHelp('Consume all items in the queue.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $callback = function ($message) use ($output) {
            $output->writeln('--------------------');
            $output->writeln('Received: ' . $message->body);
            $output->writeln('Working...');
            sleep(3);
            $output->writeln('Finished: ' . $message->body);
        };

        $connection = ConnectionFactory::getConnection();
        $channel = $connection->channel();
        $channel->queue_declare(Queue::NAME);
        $channel->basic_consume(Queue::NAME, '', false, true, false, false, $callback);
        $output->writeln("Consuming messages on " . Queue::NAME . " queue.");
        $output->writeln("ctl-c to exit.");

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
