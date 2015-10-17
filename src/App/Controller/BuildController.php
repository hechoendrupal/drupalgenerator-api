<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BuildController
 * @package App\Controller
 */
class BuildController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function chain()
    {
        $console = sprintf(
            'php %s/%s --root=%s ',
            ROOT_PATH,
            $this->app['config']['generator']['console'],
            $this->app['config']['generator']['drupal'],
            'about'
        );

        $process = $this->app['process'];
        $process->setCommandLine($console);
        $process->setWorkingDirectory(ROOT_PATH.'/');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return new JsonResponse(['message'=>$process->getOutput()]);
    }
}
