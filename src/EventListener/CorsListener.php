<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CorsListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response instanceof RedirectResponse) {
            // Ajoutez ici vos en-têtes CORS
            $response->headers->set('Access-Control-Allow-Origin', 'https://pro.testdwm.fr');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            // Ajoutez d'autres en-têtes si nécessaire
        }
    }
}
