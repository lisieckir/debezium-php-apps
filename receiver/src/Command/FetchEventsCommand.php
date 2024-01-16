<?php

namespace App\Command;

use App\Serializer\Serializer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Psr\Log\LoggerInterface;

#[AsCommand(name: 'app:receive-events', hidden: false)]
class FetchEventsCommand extends Command
{
    private const TOPIC_NAME = 'dbserver1.app.employee';
    private const KAFKA_DSN = 'kafka:9092';

    public function __construct(
        private Serializer $serializer,
        private LoggerInterface $loggerInterface
    ) {
        parent::__construct(null);
    } 

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionFactory = new RdKafkaConnectionFactory([
            'global' => [
                'group.id' => uniqid('', true),
                'metadata.broker.list' => self::KAFKA_DSN,
                'enable.auto.commit' => 'false',
            ],
            'topic' => [
            ],
        ]);

        $context = $connectionFactory->createContext();
        $fooQueue = $context->createTopic(self::TOPIC_NAME);

        $consumer = $context->createConsumer($fooQueue);
        $consumer->setSerializer($this->serializer);

        for(;;) {
            $message = $consumer->receive();
            if(!$message) {
                continue;
            }
            $kafkaMessage = $message->getKafkaMessage();

            if($kafkaMessage->payload === null) {
                continue;
            }
            
            $kafkaMessagePayload = json_decode($kafkaMessage->payload, true);

            $logContext = [
                'entity' => json_decode($kafkaMessage->key, true),
                'table' => $kafkaMessagePayload['source']['table'],
                'action' => ( ($kafkaMessagePayload['before'] === null) ? 'create' : ( ($kafkaMessagePayload['after'] !== null) ? 'edit' : 'remove') ),
            ];

            $this->loggerInterface->info('Received event', $logContext);

            $consumer->acknowledge($message);
        }

        return 0;
    }
}