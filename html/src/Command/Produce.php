<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Amqp\ConnectionFactory;
use App\Amqp\Queue;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputOption;

class Produce extends Command
{
    protected static $defaultName = 'amqp:produce';
    const OPTION_NAME = 'id';

    protected function configure()
    {
        $this->setDescription('Add a user to the queue.')
            ->setHelp('Add a user to the queue.');

        $this->addOption(
            self::OPTION_NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Who should be added to the queue?',
            'Default'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $option = $input->getOption(self::OPTION_NAME);

        $connection = ConnectionFactory::getConnection();
        $channel = $connection->channel();
        $channel->queue_declare(Queue::NAME);
        $message = new AMQPMessage($option);
        $channel->basic_publish($message, '', Queue::NAME);
        $output->writeln(
            'Added ' .self::OPTION_NAME . ' to the queue: '. $option
        );

        $channel->close();
        $connection->close();
    }
}
