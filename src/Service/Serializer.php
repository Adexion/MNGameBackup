<?php

namespace ModernGame\Service;

use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, $format, array $context = null): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function toArray($data): array
    {
        return json_decode($this->serializer->serialize($data, 'json'), true);
    }

    public function mergeDataWithEntity($entity, $data): array
    {
        return array_filter(
            array_merge($this->toArray($entity), $data)
        );
    }
}