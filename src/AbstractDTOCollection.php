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
use Gears\DTO\Exception\InvalidCollectionTypeException;
use Gears\DTO\Exception\InvalidParameterException;
use Gears\Immutability\ImmutabilityBehaviour;

/**
 * Abstract immutable Data Transfer Object collection.
 */
abstract class AbstractDTOCollection implements DTOCollection
{
    use ImmutabilityBehaviour, PayloadBehaviour {
        PayloadBehaviour::__call insteadof ImmutabilityBehaviour;
        __call as private payloadCall;
    }

    /**
     * DTOCollection constructor.
     *
     * @param array<string, mixed> $elements
     */
    final protected function __construct(array $elements)
    {
        $this->assertImmutable();

        $this->verifyElementsType($elements);

        $this->setPayload(['elements' => \array_values($elements)]);
    }

    /**
     * {@inheritdoc}
     */
    final public static function fromElements(array $elements): DTOCollection
    {
        return new static($elements);
    }

    /**
     * {@inheritdoc}
     */
    final public function getElements(): \Traversable
    {
        return $this->get('elements');
    }

    /**
     * {@inheritdoc}
     */
    final public function getIterator(): \Traversable
    {
        return $this->get('elements');
    }

    /**
     * @return string[]
     */
    final public function __sleep(): array
    {
        throw new DTOException(\sprintf('DTO collection "%s" cannot be serialized.', static::class));
    }

    final public function __wakeup(): void
    {
        throw new DTOException(\sprintf('DTO collection "%s" cannot be unserialized.', static::class));
    }

    /**
     * @return array<string, mixed>
     */
    final public function __serialize(): array
    {
        throw new DTOException(\sprintf('DTO collection "%s" cannot be serialized.', static::class));
    }

    /**
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    final public function __unserialize(array $data): void
    {
        throw new DTOException(\sprintf('DTO collection "%s" cannot be unserialized.', static::class));
    }

    /**
     * Verify collection elements type.
     *
     * @param mixed[] $elements
     *
     * @throws InvalidCollectionTypeException
     * @throws InvalidParameterException
     */
    private function verifyElementsType(array $elements): void
    {
        $allowedType = $this->getAllowedType();
        if ($allowedType !== DTO::class
            && (!\class_exists($allowedType) || !\in_array(DTO::class, \class_implements($allowedType), true))
        ) {
            throw new InvalidCollectionTypeException(\sprintf(
                'Allowed class type for "%s" should be a "%s", "%s" given.',
                static::class,
                DTO::class,
                $allowedType
            ));
        }

        foreach ($elements as $element) {
            if (!\is_object($element) || !\is_a($element, $allowedType)) {
                throw new InvalidParameterException(\sprintf(
                    'All elements of "%s" should be instances of "%s", "%s" given.',
                    static::class,
                    $allowedType,
                    \is_object($element) ? \get_class($element) : \gettype($element)
                ));
            }
        }
    }

    /**
     * Transform collection elements for output.
     *
     * @param mixed[] $elements
     *
     * @return \Traversable<mixed>
     */
    final protected function outputElements(array $elements): \Traversable
    {
        return new \ArrayIterator($elements);
    }

    /**
     * Get allowed DTO type for collection elements.
     *
     * @return string
     */
    abstract protected function getAllowedType(): string;

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
