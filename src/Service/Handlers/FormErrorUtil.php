<?php

namespace App\Service\Handlers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait FormErrorUtil
{
    private function getFormErrors($form)
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return $errors;
    }

    private function handleForm(HandlerInterface $handler, Request $request): Response
    {
        $result = $handler->handle($request);

        if ($result['success']) {
            return new JsonResponse($result);
        }

        return $this->json(['success' => false, 'errors' => $result['errors']], Response::HTTP_BAD_REQUEST);
    }
}
