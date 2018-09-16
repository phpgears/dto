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

use Gears\DTO\Exception\InvalidParameterException;

/**
 * Payload behaviour.
 */
trait PayloadBehaviour
{
    /**
     * @var mixed[]
     */
    protected $payload = [];

    /**
     * Set payload.
     *
     * @param array<string, mixed> $parameters
     */
    final protected function setPayload(array $parameters): void
    {
        $this->payload = [];

        foreach ($parameters as $parameter => $value) {
            $this->setPayloadParameter($parameter, $value);
        }
    }

    /**
     * Set payload attribute.
     *
     * @param string $parameter
     * @param mixed  $value
     */
    final protected function setPayloadParameter(string $parameter, $value): void
    {
        $this->payload[$parameter] = $value;
    }

    /**
     * Check parameter existence.
     *
     * @param string $parameter
     *
     * @return bool
     */
    final public function has(string $parameter): bool
    {
        return \array_key_exists($parameter, $this->payload);
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
        if (!\array_key_exists($parameter, $this->payload)) {
            throw new InvalidParameterException(\sprintf(
                'Payload parameter %s on %s does not exist',
                $parameter,
                static::class
            ));
        }

        $value = $this->payload[$parameter];

        $transformer = $this->getParameterTransformerMethod($parameter);
        if (\method_exists($this, $transformer)) {
            $value = $this->$transformer($value);
        }

        return $value;
    }

    /**
     * Export payload.
     *
     * @return array<string, mixed>
     */
    final public function getPayload(): array
    {
        $payload = [];

        foreach (\array_keys($this->payload) as $parameter) {
            $payload[$parameter] = $this->get($parameter);
        }

        return $payload;
    }

    /**
     * Get parameter transformer getter method name.
     *
     * @param string $parameter
     *
     * @return string
     */
    protected function getParameterTransformerMethod(string $parameter): string
    {
        return 'output' . \ucfirst($parameter);
    }
}
