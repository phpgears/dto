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
abstract class AbstractScalarDTO implements DTO
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
        $this->checkImmutability();

        $this->setPayload($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     */
    final protected function checkParameterType(&$value): void
    {
        if ($value instanceof DTOCollection) {
            $value = \iterator_to_array($value->getElements());
        }

        if (\is_array($value)) {
            foreach ($value as $val) {
                $this->checkParameterType($val);
            }
        } elseif ($value !== null && !\is_scalar($value) && !$value instanceof self) {
            throw new InvalidScalarParameterException(\sprintf(
                'Class %s can only accept scalar payload parameters, %s given',
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
        return [DTO::class];
    }
}
