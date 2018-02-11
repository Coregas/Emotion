<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->map(['GET', 'POST'],'/', 'index_controller:mainAction');

