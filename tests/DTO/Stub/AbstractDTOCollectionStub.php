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

namespace Gears\DTO\Tests\Stub;

use Gears\DTO\AbstractDTOCollection;

/**
 * AbstractDTOCollection stub class.
 */
class AbstractDTOCollectionStub extends AbstractDTOCollection
{
    /**
     * {@inheritdoc}
     */
    protected function getAllowedType(): string
    {
        return AbstractDTOStub::class;
    }
}
