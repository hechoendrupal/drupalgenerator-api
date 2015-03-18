<?php

use HechoEnDrupal\DrupalGenerator\Controller\ApiController;

$app['api_controller'] = $app->share(function() use ($app) {
    return new ApiController();
});
