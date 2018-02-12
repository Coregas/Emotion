<?php
// Routes

$app->map(['GET', 'POST'],'/', 'index_controller:mainAction');
$app->post('/new-message', 'index_controller:ajaxAction');

