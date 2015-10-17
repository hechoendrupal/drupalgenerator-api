<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use DerAlex\Silex\YamlConfigServiceProvider;
use App\RouteLoader;
use Carbon\Carbon;

date_default_timezone_set('America/Los_Angeles');

$app['debug'] = true;
$app['log.level'] = Monolog\Logger::DEBUG;
$app['api.version'] = "v1";
$app['api.endpoint'] = "/api";

define("ROOT_PATH", __DIR__ . "/..");

$app->register(new ServiceControllerServiceProvider());
$app->register(new YamlConfigServiceProvider(ROOT_PATH . '/config.yml'));
$app->register(new HttpCacheServiceProvider(), array("http_cache.cache_dir" => ROOT_PATH . "/storage/cache", ));
$app->register(
    new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application"
    )
);
$app['finder'] = function () use ($app) {
    return new Finder();
};
$app['process'] = function () use ($app) {
    return new Process(null);
};

$routesLoader = new RouteLoader($app);
$routesLoader->bindRoutesToControllers();

$app->error(
    function (\Exception $e, $code) use ($app) {
        $app['monolog']->addError($e->getMessage());
        $app['monolog']->addError($e->getTraceAsString());
        return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()));
    }
);

return $app;
