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

use Gears\Immutability\ImmutabilityBehaviour;

/**
 * Abstract immutable Data Transfer Object.
 */
abstract class AbstractDTO implements DTO
{
    use ImmutabilityBehaviour, PayloadBehaviour {
        PayloadBehaviour::__call insteadof ImmutabilityBehaviour;
    }

    /**
     * AbstractDTO constructor.
     *
     * @param array<string, mixed> $parameters
     */
    final protected function __construct(array $parameters)
    {
        $this->assertImmutable();

        $this->setPayload($parameters);
    }

    /**
     * @return mixed[]
     */
    final public function __sleep(): array
    {
        return ['payload'];
    }

    final public function __wakeup(): void
    {
        $this->assertImmutable();
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
     *
     * @return string[]
     */
    final protected function getAllowedInterfaces(): array
    {
        return [DTO::class, \Serializable::class];
    }
}
