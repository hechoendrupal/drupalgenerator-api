<?php

use SimpleForm\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app = new Application();
$app->register(new MonologServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpCacheServiceProvider());
$app->register(new ValidatorServiceProvider());

return $app;
