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

/**
 * Abstract immutable Data Transfer Object.
 */
abstract class AbstractDTO implements DTO
{
    use PayloadBehaviour;

    /**
     * AbstractDTO constructor.
     *
     * @param iterable<mixed> $payload
     */
    final protected function __construct(iterable $payload)
    {
        $this->setPayload(\is_array($payload) ? $payload : \iterator_to_array($payload));
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
}
