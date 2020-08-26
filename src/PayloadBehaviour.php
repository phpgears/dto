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

use Gears\DTO\Exception\DTOViolationException;
use Gears\DTO\Exception\InvalidMethodCallException;
use Gears\DTO\Exception\InvalidParameterException;
use Gears\Immutability\Exception\ImmutabilityViolationException;
use Gears\Immutability\ImmutabilityBehaviour;

/**
 * Payload behaviour.
 */
trait PayloadBehaviour
{
    use ImmutabilityBehaviour {
        __call as private immutabilityCall;
        getImmutabilityAllowedPublicMethods as private defaultGetImmutabilityAllowedPublicMethods;
    }

    /**
     * @var array<string[]>
     */
    protected static $payloadDefinitionMap = [];

    /**
     * Single set payload call check.
     *
     * @var bool
     */
    private $payloadAlreadySet = false;

    /**
     * Set payload.
     *
     * @param array<string, mixed> $payload
     */
    private function setPayload(array $payload): void
    {
        $this->assertPayloadSingleCall();

        $class = static::class;

        if (!isset(static::$payloadDefinitionMap[$class])) {
            $this->assertPayloadCallConstraints();

            static::$payloadDefinitionMap[$class] = $this->getPayloadDefinition();

            $this->assertImmutable();
        }

        $reflection = new \ReflectionClass($this);
        foreach ($payload as $parameter => $value) {
            $this->setPayloadParameter($reflection, $parameter, $value);
        }
    }

    /**
     * Set payload attribute.
     *
     * @param \ReflectionClass $reflection
     * @param string           $parameter
     * @param mixed            $value
     */
    private function setPayloadParameter(\ReflectionClass $reflection, string $parameter, $value): void
    {
        if (!\in_array($parameter, static::$payloadDefinitionMap[static::class], true)) {
            throw new InvalidParameterException(
                \sprintf('Payload parameter "%s" on "%s" does not exist', $parameter, static::class)
            );
        }

        $property = $reflection->getProperty($parameter);
        $property->setAccessible(true);
        $property->setValue($this, $value);
    }

    /**
     * Get payload definition.
     *
     * @return string[]
     */
    private function getPayloadDefinition(): array
    {
        $excludedProperties = \array_filter(\array_map(
            static function (\ReflectionProperty $property): ?string {
                return !$property->isStatic() ? $property->getName() : null;
            },
            (new \ReflectionClass(PayloadBehaviour::class))->getProperties()
        ));

        return \array_filter(\array_map(
            static function (\ReflectionProperty $property) use ($excludedProperties): ?string {
                return !$property->isStatic() && !\in_array($property->getName(), $excludedProperties, true)
                    ? $property->getName()
                    : null;
            },
            (new \ReflectionClass($this))->getProperties()
        ));
    }

    /**
     * Assert single call.
     *
     * @throws ImmutabilityViolationException
     */
    final private function assertPayloadSingleCall(): void
    {
        if ($this->payloadAlreadySet) {
            throw new DTOViolationException(\sprintf('Payload already set for DTO "%s"', static::class));
        }

        $this->payloadAlreadySet = true;
    }

    /**
     * Assert payload set call constraints.
     *
     * @throws DTOViolationException
     */
    private function assertPayloadCallConstraints(): void
    {
        $stack = $this->getPayloadFilteredCallStack();

        $callingMethods = ['__construct', '__wakeup', '__unserialize'];
        if ($this instanceof \Serializable) {
            $callingMethods[] = 'unserialize';
        }

        if (!isset($stack[1]) || !\in_array($stack[1]['function'], $callingMethods, true)) {
            throw new DTOViolationException(\sprintf(
                'DTO payload set available only through "%s" methods, called from "%s"',
                \implode('", "', $callingMethods),
                isset($stack[1]) ? static::class . '::' . $stack[1]['function'] : 'unknown'
            ));
        }
    }

    /**
     * Get filter call stack.
     *
     * @return mixed[]
     */
    private function getPayloadFilteredCallStack(): array
    {
        $stack = \debug_backtrace();

        while (\count($stack) > 0 && $stack[0]['function'] !== 'setPayload') {
            \array_shift($stack);
        }

        return $stack;
    }

    /**
     * {@inheritdoc}
     * Replace original to only accept call from setPayload().
     */
    private function assertImmutabilityCallConstraints(): void
    {
        $stack = $this->getImmutabilityFilteredCallStack();

        if (!isset($stack[1]) || $stack[1]['function'] !== 'setPayload') {
            throw new ImmutabilityViolationException(\sprintf(
                'Immutability check available only through "setPayload" method, called from "%s"',
                isset($stack[1]) ? static::class . '::' . $stack[1]['function'] : 'unknown'
            ));
        }
    }

    /**
     * {@inheritdoc}
     * Extend original to allow payload getters.
     *
     * @return string[]
     */
    private function getImmutabilityAllowedPublicMethods(): array
    {
        $allowedPublicMethods = \array_unique(\array_merge(
            $this->defaultGetImmutabilityAllowedPublicMethods(),
            \array_map(
                static function (string $parameter): string {
                    return 'get' . \ucfirst($parameter);
                },
                static::$payloadDefinitionMap[static::class]
            )
        ));

        \sort($allowedPublicMethods);

        return $allowedPublicMethods;
    }

    /**
     * Get parameter.
     *
     * @param string $parameter
     *
     * @throws InvalidParameterException
     *
     * @return mixed
     */
    final public function get(string $parameter)
    {
        if (!\in_array($parameter, static::$payloadDefinitionMap[static::class], true)) {
            throw new InvalidParameterException(\sprintf(
                'Payload parameter "%s" on "%s" does not exist',
                $parameter,
                static::class
            ));
        }

        $method = 'get' . \ucfirst($parameter);
        if (\method_exists($this, $method)) {
            /** @var callable $callable */
            $callable = [$this, $method];

            return $callable();
        }

        $property = (new \ReflectionClass($this))->getProperty($parameter);
        $property->setAccessible(true);

        return $property->getValue($this);
    }

    /**
     * Export payload.
     *
     * @return array<string, mixed>
     */
    final public function getPayload(): array
    {
        $reflection = new \ReflectionClass($this);

        $payload = [];
        foreach (static::$payloadDefinitionMap[static::class] as $parameter) {
            $method = 'get' . \ucfirst($parameter);
            if (\method_exists($this, $method)) {
                /** @var callable $callable */
                $callable = [$this, $method];

                $payload[$parameter] = $callable();
            } else {
                $property = $reflection->getProperty($parameter);
                $property->setAccessible(true);

                $payload[$parameter] = $property->getValue($this);
            }
        }

        return $payload;
    }

    /**
     * Magic getters call.
     *
     * @param string  $methodName
     * @param mixed[] $arguments
     *
     * @throws InvalidMethodCallException
     * @throws InvalidParameterException
     *
     * @return mixed
     */
    final public function __call(string $methodName, array $arguments)
    {
        if (\preg_match('/^get(?P<parameter>[A-Z][a-zA-Z0-9-_]*)$/', $methodName, $matches) !== 1) {
            throw new InvalidMethodCallException(
                \sprintf('Method "%s::%s" does not exist', static::class, $methodName)
            );
        }

        if (\count($arguments) !== 0) {
            throw new InvalidMethodCallException(\sprintf(
                'Method "%s::%s" should be called with no parameters',
                static::class,
                $methodName
            ));
        }

        $parameter = $matches['parameter'];

        return \in_array(\lcfirst($parameter), static::$payloadDefinitionMap[static::class], true)
            ? $this->get(\lcfirst($parameter))
            : $this->get($parameter);
    }
}
