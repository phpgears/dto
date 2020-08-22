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
 * Abstract immutable Data Transfer Object collection.
 */
abstract class AbstractDTOCollection implements DTOCollection
{
    use PayloadCollectionBehaviour;

    /**
     * DTOCollection constructor.
     *
     * @param iterable<DTO> $elements
     */
    final protected function __construct(iterable $elements)
    {
        $this->setPayload([
            'elements' => \is_array($elements) ? \array_values($elements) : \iterator_to_array($elements),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    final public static function fromElements(iterable $elements): DTOCollection
    {
        return new static($elements);
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    final protected function getAllowedInterfaces(): array
    {
        return [DTOCollection::class];
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
