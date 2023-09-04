<?php

namespace App\Service;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException
{

    public function __construct(private ServiceExceptionData $serviceExceptionData)
    {

        $statusCode = $serviceExceptionData->getStatusCode();
        $message = $serviceExceptionData->getType();

        parent::__construct($statusCode, $message);
    }

    public function getExceptionData(): ServiceExceptionData
    {
        return $this->serviceExceptionData;
    }

}
