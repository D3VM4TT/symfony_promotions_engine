<?php

namespace App\Service\Serializer;

use App\Event\AfterDtoCreatedEvent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DTOSerializer implements SerializerInterface
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(
                    classMetadataFactory: new ClassMetadataFactory(new AnnotationLoader()),
                    nameConverter: new CamelCaseToSnakeCaseNameConverter()
                ),
            ],
            [
                new JsonEncoder()
            ]);
    }

    private SerializerInterface $serializer;


    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $dto =  $this->serializer->deserialize($data, $type, $format, $context);

        $event = new AfterDtoCreatedEvent($dto);

        $this->eventDispatcher->dispatch($event, AfterDtoCreatedEvent::NAME);

        return $dto;
    }
}
