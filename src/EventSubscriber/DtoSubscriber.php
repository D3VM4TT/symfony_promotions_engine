<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Service\ServiceException;
use App\Service\ServiceExceptionData;
use App\Service\ValidationExceptionData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{

    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterDtoCreatedEvent::NAME => 'validateDto'
        ];
    }


    public function validateDto(AfterDtoCreatedEvent $event)
    {
        $errors = $this->validator->validate($event->getDto());

        if (count($errors) > 0) {
            $serviceExceptionData = new ValidationExceptionData(422, 'ConstraintViolationList', $errors);
            throw new ServiceException($serviceExceptionData);
        }
    }
}
