<?php

namespace App;

use Silex\Application;
use App\Controller\LocaleController;
use App\Controller\BuildController;

/**
 * Class RouteLoader
 * @package App
 */
class RouteLoader
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
    */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    /**
     * @return void
     */
    private function instantiateControllers()
    {
        $this->app['locale.controller'] = $this->app->share(
            function () {
                return new LocaleController($this->app);
            }
        );
        $this->app['build.controller'] = $this->app->share(
            function () {
                return new BuildController($this->app);
            }
        );

        return;
    }

    /**
     * @return void
     */
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/locales', "locale.controller:getAll");
        $api->post('/build', "build.controller:chain");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);

        return;
    }
}
