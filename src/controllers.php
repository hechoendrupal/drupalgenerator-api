<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

$app->get('/', function(){ return ""; });
$app->get('/api/v1/show-commands', 'api_controller:showCommands');
