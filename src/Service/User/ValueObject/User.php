<?php

namespace Emotion\Service\User\ValueObject;

use DateTime;

class User
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var
     */
    private $email;
    /**
     * @var DateTime
     */
    private $birthdate;
    /**
     * @var int
     */
    private $age;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        $email,
        DateTime $birthdate,
        int $age
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }
}