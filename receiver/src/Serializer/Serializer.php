<?php

namespace App\Serializer;

use Enqueue\RdKafka\RdKafkaMessage;
use Enqueue\RdKafka\Serializer as RdKafkaSerializer;

class Serializer implements RdKafkaSerializer
{
    public function toString(RdKafkaMessage $message): string 
    { 
        return '';
    }

    public function toMessage(string $string = null): RdKafkaMessage 
    {
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf(
                'The malformed json given. Error %s and message %s',
                json_last_error(),
                json_last_error_msg()
            ));
        }
        return new RdKafkaMessage('', [], []);
     }
    
}