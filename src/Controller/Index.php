<?php
namespace Emotion\Controller;
use Emotion\Service\FormValidator;
use Slim\Http\Request;
use Slim\Views\PhpRenderer;
use Emotion\Gateway\User;

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

    private $userGateway;

    public function __construct(
        PhpRenderer $view,
        FormValidator $formValidator,
        User $userGateway
    ) {
        $this->view = $view;
        $this->formValidator = $formValidator;
        $this->userGateway = $userGateway;
    }
    public function mainAction($request, $response)
    {
        $this->validateForm($request);

        dump($this->userGateway->getOneUser());
        return $this->view->render($response, 'index.phtml', [
            'formErrors' => $this->formValidator->getErrors(),
            'fromValues' => $this->formValidator->getEntries()
        ]);
    }

    /**
     * @param Request $request
     */
    private function validateForm($request)
    {
        if ($this->formValidator->isFormSubmited()) {
            $this->formValidator->addEntries([
                'first_name' => $request->getParam('first-name'),
                'last_name' => $request->getParam('last-name'),
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
            dump($this->formValidator->getErrors());
        } else {
            $this->formValidator->addEntries([
                'first_name' => '',
                'last_name' => '',
                'birthdate' => '',
                'email' => '',
                'message' => '',
            ]);

        }
    }
}