<?php

namespace HechoEnDrupal\DrupalGenerator\Controller;

use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    public function showCommands()
    {
        return new Response('show commands');
    }

}
