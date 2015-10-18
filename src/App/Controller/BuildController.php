<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BuildController
 * @package App\Controller
 */
class BuildController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function chain(Request $request)
    {
        $commands = $this->getDataFromRequest($request);

        $dumper = $this->app['dumper'];
        $chain = $dumper->dump($commands, 10);
        $chainFile = $this->createChainFile($chain);

        $console = sprintf(
            'php %s/%s --root=%s chain --file=%s',
            ROOT_PATH,
            $this->app['config']['generator']['console'],
            $this->app['config']['generator']['drupal'],
            $chainFile
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

    /**
     * @param Request $request
     * @return array
     */
    private function getDataFromRequest(Request $request)
    {
        return [
          "commands" => $request->request->get("commands")
        ];
    }

    /**
     * @param $chain
     * @return string
     */
    private function createChainFile($chain) {
        $chainFile = ROOT_PATH . '/storage/chain/file.yml';

        if (!is_dir(dirname($chainFile))) {
            mkdir(dirname($chainFile));
        }
        if (file_exists($chainFile)) {
            unlink($chainFile);
        }
        file_put_contents($chainFile, $chain);

        return $chainFile;
    }

}
