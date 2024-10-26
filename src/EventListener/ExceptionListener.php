<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if ($request->isXmlHttpRequest() && $exception instanceof HttpException) {
            // Customize the response for AJAX requests
            $errors = json_decode($exception->getMessage(), true);
            $response = new JsonResponse($errors, $exception->getStatusCode());
            $event->setResponse($response);
        }
    }
}
