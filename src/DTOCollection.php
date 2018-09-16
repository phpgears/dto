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
 */
interface DTOCollection extends DTO
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
     * Get traversable from collection.
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable;
}
