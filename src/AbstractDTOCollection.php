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
        $this->setPayload(['elements' => $elements]);
    }

    /**
     * {@inheritdoc}
     */
    final public static function fromElements(iterable $elements)
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
}
