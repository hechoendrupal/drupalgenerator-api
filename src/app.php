<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Dumper;
use DerAlex\Silex\YamlConfigServiceProvider;
use App\RouteLoader;
use Carbon\Carbon;

date_default_timezone_set('America/Los_Angeles');

$app['debug'] = true;
$app['log.level'] = Monolog\Logger::DEBUG;
$app['api.version'] = "v1";
$app['api.endpoint'] = "/api";

define("ROOT_PATH", __DIR__ . "/..");

//accepting JSON
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

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

$app['finder'] = function() {
    return new Finder();
};
$app['process'] = function() {
    return new Process(null);
};
$app['dumper'] = function() {
    return new Dumper();
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
