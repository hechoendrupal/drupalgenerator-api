<?php

chdir(__DIR__ . '/../');
require 'vendor/autoload.php';

$app = require 'src/app.php';
require 'app/config/dev.php';
require 'src/container.php';
require 'src/controllers.php';

$app->run();
