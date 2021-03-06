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
 * Data Transfer Object collection interface.
 *
 * @extends \IteratorAggregate<DTO>
 */
interface DTOCollection extends DTO, \IteratorAggregate
{
    /**
     * Create collection from elements.
     *
     * @param DTO[] $elements
     *
     * @return self
     */
    public static function fromElements(array $elements): self;

    /**
     * Get elements as traversable.
     *
     * @return \Traversable<DTO>
     */
    public function getElements(): \Traversable;

    /**
     * Get traversable from collection.
     *
     * @return \Traversable<DTO>
     */
    public function getIterator(): \Traversable;
}
