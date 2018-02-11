<?php
namespace Emotion\Controller;
use Slim\Views\PhpRenderer;

class Index
{
    private $view;

    public function __construct(
        PhpRenderer $view
    ) {
        $this->view = $view;
    }
    public function mainAction($request, $response, $args)
    {
        return $this->view->render($response, 'index.phtml', $args);
    }
}