<?php

namespace App\EventListener;

use http\Client\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse([
                'type' => 'ConstraintViolationList',
                'title' => 'An error occurred',
                'description' => $exception->getMessage(),
                'violations' => [
                    [
                        'propertyPath' => 'title',
                        'message' => 'This value should not be blank.'
                    ]
                ]
            ]
        );

        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }

}
