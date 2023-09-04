<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event): void
    {
        // TODO: Handle other types of exceptions other than ServiceException
        $exception = $event->getThrowable();

        $exceptionData = $exception->getExceptionData();

        $response = new JsonResponse($exceptionData->toArray());

        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }

}
