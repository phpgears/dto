<?php

/*
 * dto (https://github.com/phpgears/dto).
 * General purpose immutable Data Transfer Objects for PHP.
 *
 * @license MIT
 * @link https://github.com/phpgears/dto
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Gears\DTO;

/**
 * Abstract immutable and only scalar values Data Transfer Object.
 */
abstract class AbstractScalarDTO implements DTO, \Serializable
{
    use ScalarPayloadBehaviour;

    /**
     * AbstractSerializableDTO constructor.
     *
     * @param array<string, mixed> $parameters
     */
    final protected function __construct(array $parameters)
    {
        $this->setPayload($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    final protected function getAllowedInterfaces(): array
    {
        return [DTO::class, \Serializable::class];
    }

    /**
     * @return array<string, mixed>
     */
    final public function __serialize(): array
    {
        return ['payload' => $this->getPayload()];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    final public function __unserialize(array $data): void
    {
        $this->setPayload($data['payload']);
    }

    /**
     * {@inheritdoc}
     */
    final public function serialize(): string
    {
        return \serialize($this->getPayload());
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $serialized
     */
    final public function unserialize($serialized): void
    {
        $this->setPayload(\unserialize($serialized, ['allowed_classes' => false]));
    }
}
