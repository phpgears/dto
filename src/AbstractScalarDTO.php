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

use Gears\DTO\Exception\InvalidScalarParameterException;
use Gears\Immutability\ImmutabilityBehaviour;

/**
 * Abstract immutable and only scalar values Data Transfer Object.
 */
abstract class AbstractScalarDTO implements DTO, \Serializable
{
    use ImmutabilityBehaviour, ScalarPayloadBehaviour {
        ScalarPayloadBehaviour::__call insteadof ImmutabilityBehaviour;
        ScalarPayloadBehaviour::checkParameterType as private scalarCheckParameterType;
    }

    /**
     * AbstractSerializableDTO constructor.
     *
     * @param array<string, mixed> $parameters
     */
    final protected function __construct(array $parameters)
    {
        $this->assertImmutable();

        $this->setPayload($parameters);
    }

    /**
     * @return array<string, mixed>
     */
    final public function __serialize(): array
    {
        return ['payload' => $this->payload];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    final public function __unserialize(array $data): void
    {
        $this->assertImmutable();

        $this->setPayload($data['payload']);
    }

    /**
     * {@inheritdoc}
     */
    final public function serialize(): string
    {
        return \serialize($this->payload);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $serialized
     */
    final public function unserialize($serialized): void
    {
        $this->assertImmutable();

        $this->payload = \unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     */
    final protected function checkParameterType($value): void
    {
        if ($value instanceof DTO) {
            $value = $value->getPayload();
        }

        if (\is_array($value)) {
            foreach ($value as $val) {
                $this->checkParameterType($val);
            }
        } elseif ($value !== null && !\is_scalar($value)) {
            throw new InvalidScalarParameterException(\sprintf(
                'Class "%s" can only accept scalar payload parameters, "%s" given.',
                self::class,
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }
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
}
