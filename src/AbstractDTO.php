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

use Gears\DTO\Exception\DTOException;
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
     * @return string[]
     */
    final public function __sleep(): array
    {
        throw new DTOException(\sprintf('DTO "%s" cannot be serialized', static::class));
    }

    final public function __wakeup(): void
    {
        throw new DTOException(\sprintf('DTO "%s" cannot be unserialized', static::class));
    }

    /**
     * @return array<string, mixed>
     */
    final public function __serialize(): array
    {
        throw new DTOException(\sprintf('DTO "%s" cannot be serialized', static::class));
    }

    /**
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    final public function __unserialize(array $data): void
    {
        throw new DTOException(\sprintf('DTO "%s" cannot be unserialized', static::class));
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
