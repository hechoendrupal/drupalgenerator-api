<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class LocaleController
 * @package App\Controller
 */
class LocaleController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        $finder = $this->app['finder'];
        $finder->in(
            sprintf('%s/vendor/drupal/console/config/translations/', ROOT_PATH)
        );
        $finder->directories();

        $locales = [];
        foreach ($finder as $locale) {
            $locales[] = $locale->getRelativePathname();
        }

        return new JsonResponse($locales);
    }
}
