<?php
namespace Emotion\Service\Message;

use Emotion\Gateway\Message;
use Emotion\Gateway\User;
use Emotion\Service\Message\Factory\MessageFactory;
use Emotion\Service\User\Factory\UserFactory;


class MessageFunctions
{
    /**
     * @var Message
     */
    private $messageGateway;
    /**
     * @var User
     */
    private $userGateway;
    /**
     * @var MessageFactory
     */
    private $messageFactory;
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(
        Message $messageGateway,
        User $userGateway,
        MessageFactory $messageFactory,
        UserFactory $userFactory
    ) {
        $this->messageGateway = $messageGateway;
        $this->userGateway = $userGateway;
        $this->messageFactory = $messageFactory;
        $this->userFactory = $userFactory;
    }

    /**
     * @param int|null $pageNo
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMessagePagination(int $pageNo = null)
    {
        $messagePagination = $this->messageGateway->getMessagePagination($pageNo);

        return $messagePagination;
    }

    public function getMessagesFroPage(int $pageNo = null)
    {
        $messagePagination = $this->getMessagePagination($pageNo);
        $messagesForPage = [];
        $userIds = [];
        foreach ($messagePagination->items() as $message) {
            $message = $this->messageFactory->buildFromData($message);
            $userIds[] = $message->getUserId();
            $messagesForPage[] = $message;
        }
        $users = $this->userGateway->getUsersByIds($userIds);
        foreach ($messagesForPage as $key => &$message) {
            $user = $this->userFactory->buildFromData($users[$key]);
            $message->setUser($user);
        }

        return array_reverse($messagesForPage);
    }
}