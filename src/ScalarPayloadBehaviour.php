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

/**
 * Scalar payload behaviour.
 */
trait ScalarPayloadBehaviour
{
    use PayloadBehaviour {
        setPayloadParameter as private defaultSetPayloadParameter;
    }

    /**
     * Set payload parameter.
     *
     * @param string $parameter
     * @param mixed  $value
     */
    final protected function setPayloadParameter(string $parameter, $value): void
    {
        $this->checkParameterType($value);

        $this->payload[$parameter] = $value;
    }

    /**
     * Check only scalar types allowed.
     *
     * @param mixed $value
     *
     * @throws InvalidScalarParameterException
     */
    final protected function checkParameterType($value): void
    {
        if (\is_array($value)) {
            foreach ($value as $val) {
                $this->checkParameterType($val);
            }
        } elseif ($value !== null && !\is_scalar($value)) {
            throw new InvalidScalarParameterException(\sprintf(
                'Class %s can only accept scalar payload parameters, %s given',
                self::class,
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }
    }
}
