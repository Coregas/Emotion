<?php
namespace Emotion\Service\Message\Factory;
use DateTime;
use Emotion\Service\Message\ValueObject\Message;
use stdClass;

class MessageFactory
{
    /**
     * @param stdClass $data
     * @return Message
     */
    public function buildFromData(stdClass $data)
    {
        return new Message(
            $data->id,
            $data->user_id,
            $data->message,
            DateTime::createFromFormat('Y-m-d H:i:s', $data->time)
        );
    }
}