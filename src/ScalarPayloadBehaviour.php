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
     * {@inheritdoc}
     *
     * @param \ReflectionClass $reflection
     * @param string           $parameter
     * @param mixed            $value
     */
    private function setPayloadParameter(\ReflectionClass $reflection, string $parameter, $value): void
    {
        $this->assertPayloadParameterType($value);

        $this->defaultSetPayloadParameter($reflection, $parameter, $value);
    }

    /**
     * Check only scalar types allowed.
     *
     * @param mixed $value
     *
     * @throws InvalidScalarParameterException
     */
    private function assertPayloadParameterType($value): void
    {
        if (\is_array($value)) {
            foreach ($value as $val) {
                $this->assertPayloadParameterType($val);
            }
        } elseif ($value !== null && !\is_scalar($value)) {
            throw new InvalidScalarParameterException(\sprintf(
                'Class "%s" can only accept scalar payload parameters, "%s" given',
                self::class,
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }
    }

    /**
     * Get raw payload.
     *
     * @return array<string, mixed>
     */
    final protected function getPayloadRaw(): array
    {
        $reflection = new \ReflectionClass($this);

        $payload = [];
        foreach (static::$payloadDefinitionMap[static::class] as $parameter) {
            $property = $reflection->getProperty($parameter);
            $property->setAccessible(true);

            $payload[$parameter] = $property->getValue($this);
        }

        return $payload;
    }
}
