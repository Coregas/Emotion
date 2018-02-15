<?php
namespace Emotion\Service\User\Factory;

use DateTime;
use Emotion\Service\User\ValueObject\User;
use stdClass;

class UserFactory
{
    /**
     * @param stdClass $data
     * @return User
     */
    public function buildFromData(stdClass $data)
    {
        return new User(
                $data->id,
                $data->first_name,
                $data->last_name,
                $data->email,
                DateTime::createFromFormat('Y-m-d H:i:s', $data->birthdate),
                $this->getUserAge($data->birthdate)
        );
    }

    private function getUserAge($birthDate)
    {
        $now = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $userBirthday = DateTime::createFromFormat('Y-m-d H:m:s',$birthDate);
        $age = $now->diff($userBirthday);

        return $age->format('%y');
    }
}