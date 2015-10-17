<?php

namespace App\Controller;

use Silex\Application;

/**
 * Class LocaleController
 * @package App\Controller
 */
class Controller
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
