<?php

namespace Emotion\Service\Message\ValueObject;

use DateTime;
use Emotion\Service\User\ValueObject\User;

class Message
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $message;
    /**
     * @var DateTime
     */
    private $time;
    /**
     * @var User
     */
    private $user;

    public function __construct(
        int $id,
        int $userId,
        string $message,
        DateTime $time
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->message = $message;
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}