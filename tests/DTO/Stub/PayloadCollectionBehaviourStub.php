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

use Gears\DTO\DTOCollection;
use Gears\DTO\PayloadCollectionBehaviour;

/**
 * PayloadCollectionBehaviour stub class.
 */
class PayloadCollectionBehaviourStub implements DTOCollection
{
    use PayloadCollectionBehaviour;

    protected $invalid;

    /**
     * PayloadCollectionBehaviourStub constructor.
     *
     * @param string $parameter
     * @param mixed  $elements
     */
    protected function __construct(string $parameter, $elements)
    {
        $this->setPayload([
            $parameter => $elements,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromElements(iterable $elements): DTOCollection
    {
        return new static('elements', $elements);
    }

    /**
     * @param string $parameter
     * @param mixed  $elements
     *
     * @return DTOCollection
     */
    public static function fromInvalid(string $parameter, $elements): DTOCollection
    {
        return new static($parameter, $elements);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllowedType(): string
    {
        return AbstractDTOStub::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllowedInterfaces(): array
    {
        return [DTOCollection::class];
    }
}
