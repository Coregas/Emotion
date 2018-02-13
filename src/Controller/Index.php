<?php
namespace Emotion\Controller;
use Emotion\Service\FormValidator;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use Emotion\Gateway\User;
use Emotion\Gateway\Message;
use DateTime;

class Index
{
    /**
     * @var PhpRenderer
     */
    private $view;

    /**
     * @var FormValidator
     */
    private $formValidator;
    /**
     * @var User
     */
    private $userGateway;
    /**
     * @var Message
     */
    private $messageGateway;

    public function __construct(
        PhpRenderer $view,
        FormValidator $formValidator,
        User $userGateway,
        Message $messageGateway
    ) {
        $this->view = $view;
        $this->formValidator = $formValidator;
        $this->userGateway = $userGateway;
        $this->messageGateway = $messageGateway;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mainAction($request, $response)
    {
        if($this->validateForm($request)) {
            $userId = $this->insertUser();
            if ($userId) {
                if($this->insertMessage($userId)) {
                    return $response->withRedirect('/', 303);
                }
            }

        }

        return $this->view->render($response, 'index.phtml', [
            'formErrors' => $this->formValidator->getErrors(),
            'fromValues' => $this->formValidator->getEntries()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function ajaxAction($request, $response)
    {
        if ($this->validateForm($request)) {
            $userId = $this->insertUser();
            if ($userId) {
                $messageId = $this->insertMessage($userId);
                if ($messageId) {
                    return json_encode($this->getNewMessageData($userId, $messageId));
                }
                $data['status'] = 'error';
                return json_encode($data);
            }
            $data['status'] = 'error';
            return json_encode($data);
        } else {
            $data['status'] = 'error';
            $data['data'] = $this->formValidator->getErrors();
            return json_encode($data);
        }

    }


    private function getNewMessageData($userId, $messageId)
    {
        $message = $this->messageGateway->getMessageById($messageId);
        $message->time = (DateTime::createFromFormat('Y-m-d H:i:s', $message->time))->format('Y m d H:i');

        $user = $this->userGateway->getUserById($userId);
        $now = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

        $userBirthday = DateTime::createFromFormat('Y-m-d H:m:s',$user->birthdate);

        $age = $now->diff($userBirthday);
        $user->age = $age->format('%y');

        $data['status'] = 'success';
        $data['data'] = [
            'user' => $user,
            'message' => $message
        ];

        return $data;
    }
    /**
     * @param $request
     * @return bool
     */
    private function validateForm($request)
    {
        if ($this->formValidator->isFormSubmited()) {
            $this->formValidator->addEntries([
                'first_name' => $request->getParam('first_name'),
                'last_name' => $request->getParam('last_name'),
                'birthdate' => $request->getParam('birthdate'),
                'email' => $request->getParam('email'),
                'message' => $request->getParam('message'),
            ]);

            $this->formValidator->addRule('first_name', 'err', 'name|required');
            $this->formValidator->addRule('last_name', 'err', 'name|required');
            $this->formValidator->addRule('birthdate', 'err', 'birth_date|required');
            $this->formValidator->addRule('email', 'err', 'email');
            $this->formValidator->addRule('message', 'err', 'required');
            $this->formValidator->validate();
            if (!$this->formValidator->foundErrors()) {
                return true;
            }
        } else {
            $this->formValidator->addEntries([
                'first_name' => '',
                'last_name' => '',
                'birthdate' => '',
                'email' => '',
                'message' => '',
            ]);

        }
        return false;
    }

    /**
     * @return bool
     */
    private function insertUser()
    {
        $userId = $this->userGateway->insert([
            'first_name' => $this->formValidator->getEntry('first_name'),
            'last_name' => $this->formValidator->getEntry('last_name'),
            'email' => $this->formValidator->getEntry('email'),
            'birthdate' => $this->formValidator->getEntry('birthdate'),
        ]);

        return $userId;
    }

    /**
     * @param $userId
     * @return bool
     */
    private function insertMessage($userId)
    {
       $messageId = $this->messageGateway->insert([
            'user_id' => $userId,
            'message' => $this->formValidator->getEntry('message'),
            'time' => new DateTime()
        ]);

       return $messageId;
    }
}