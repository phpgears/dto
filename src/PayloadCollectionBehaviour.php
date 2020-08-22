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
     * @var \ArrayIterator<int, DTO>
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
                'Only "elements" parameter allowed in "%s", "%s" given',
                static::class,
                $parameter
            ));
        }

        $this->assertElementsType($value);

        $this->defaultSetPayloadParameter($reflection, $parameter, new \ArrayIterator($value));
    }

    /**
     * Assert collection elements type.
     *
     * @param mixed[] $elements
     *
     * @throws InvalidCollectionTypeException
     * @throws InvalidParameterException
     */
    private function assertElementsType(array $elements): void
    {
        $allowedType = $this->getAllowedType();
        if ($allowedType !== DTO::class
            && (!\class_exists($allowedType) || !\in_array(DTO::class, \class_implements($allowedType), true))
        ) {
            throw new InvalidCollectionTypeException(\sprintf(
                'Allowed class type for "%s" should be a "%s", "%s" given',
                static::class,
                DTO::class,
                $allowedType
            ));
        }

        foreach ($elements as $element) {
            if (!\is_object($element) || !\is_a($element, $allowedType)) {
                throw new InvalidParameterException(\sprintf(
                    'All elements of "%s" should be instances of "%s", "%s" given',
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
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     *
     * @retrun \Traversable<DTO>
     */
    final public function getIterator(): \Traversable
    {
        return $this->elements;
    }

    /**
     * Get allowed DTO type for collection elements.
     *
     * @return string
     */
    abstract protected function getAllowedType(): string;
}
