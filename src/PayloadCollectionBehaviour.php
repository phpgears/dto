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

use Gears\DTO\Exception\InvalidCollectionTypeException;
use Gears\DTO\Exception\InvalidParameterException;

/**
 * Payload collection behaviour.
 */
trait PayloadCollectionBehaviour
{
    use PayloadBehaviour {
        setPayloadParameter as private defaultSetPayloadParameter;
    }

    /**
     * Collection elements list.
     *
     * @var DTO[]
     */
    protected $elements;

    /**
     * {@inheritdoc}
     *
     * @param \ReflectionClass $reflection
     * @param string           $parameter
     * @param mixed            $value
     */
    private function setPayloadParameter(\ReflectionClass $reflection, string $parameter, $value): void
    {
        if ($parameter !== 'elements') {
            throw new InvalidParameterException(\sprintf(
                'Only "elements" parameter allowed in "%s", "%s" given.',
                static::class,
                $parameter
            ));
        }

        if (!\is_iterable($value)) {
            throw new InvalidParameterException(\sprintf(
                '"elements" parameter should be an iterable, "%s" given.',
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }
        $value = \array_values(\is_array($value) ? $value : \iterator_to_array($value));

        $this->assertPayloadElementsType($value);

        $this->defaultSetPayloadParameter($reflection, $parameter, $value);
    }

    /**
     * Assert collection elements type.
     *
     * @param mixed[] $elements
     *
     * @throws InvalidCollectionTypeException
     * @throws InvalidParameterException
     */
    private function assertPayloadElementsType(array $elements): void
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
     * {@inheritdoc}
     */
    final public function getElements(): \Traversable
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     *
     * @retrun \Traversable<DTO>
     */
    final public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Get allowed DTO type for collection elements.
     *
     * @return string
     */
    abstract protected function getAllowedType(): string;
}
