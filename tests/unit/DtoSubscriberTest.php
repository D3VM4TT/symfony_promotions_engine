<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDtoCreatedEvent;
use App\EventSubscriber\DtoSubscriber;
use App\Service\ServiceException;
use App\Tests\ServiceTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class DtoSubscriberTest extends ServiceTestCase
{

    /** @test */
    public function dto_subscriber_is_subscribed_to_AfterDtoCreatedEvent()
    {

        // Given
        $eventDispatcher = $this->container->get('event_dispatcher');

        // When
        $listeners = $eventDispatcher->getListeners('dto.created');

        // Then
        $this->assertCount(1, $listeners);
    }

    /** @test */
    public function a_dto_is_validated_after_it_has_been_created()
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-3);

        $event = new AfterDtoCreatedEvent($dto);

        $eventDispatcher = $this->container->get('event_dispatcher');

        $this->expectException(ServiceException::class);

        $eventDispatcher->dispatch($event, AfterDtoCreatedEvent::NAME);
    }

}
