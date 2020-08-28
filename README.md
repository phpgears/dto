[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto)
[![License](https://img.shields.io/github/license/phpgears/dto.svg?style=flat-square)](https://github.com/phpgears/dto/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/phpgears/dto.svg?style=flat-square)](https://travis-ci.org/phpgears/dto)
[![Style Check](https://styleci.io/repos/148840961/shield)](https://styleci.io/repos/148840961)
[![Code Quality](https://img.shields.io/scrutinizer/g/phpgears/dto.svg?style=flat-square)](https://scrutinizer-ci.com/g/phpgears/dto)
[![Code Coverage](https://img.shields.io/coveralls/phpgears/dto.svg?style=flat-square)](https://coveralls.io/github/phpgears/dto)

[![Total Downloads](https://img.shields.io/packagist/dt/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto/stats)

# DTO

General purpose immutable Data Transfer Objects for PHP

This library provides a means to implement DTO classes as long as three different implementations of general purpose abstract DTO objects you can extend from

This DTO objects are immutable as can be thanks to [gears/immutability](https://github.com/phpgears/immutability), that means once the DTO is created there is no way a value can be mutated (inside PHP limitations)

## Installation

### Composer

```
composer require phpgears/dto
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';
```

Quickly create plain immutable Data Transfer Objects by extending `Gears\DTO\AbstractDTO`, `Gears\DTO\AbstractScalarDTO` or `Gears\DTO\AbstractDTOCollection`

All the abstract classes have protected constructors that forces you to create "named constructors" in which you get to type-hint all your DTO attributes

The difference between `Gears\DTO\AbstractDTO` and `Gears\DTO\AbstractScalarDTO` is that the later verifies that all attributes are either a scalar value (null, string, int, float or bool) or an array of scalar values. Its purpose is to ensure the object can be securely serialized/unserialized. It's perfect for creating Domain Events or Commands and Queries for CQRS

```php
use Gears\DTO\AbstractScalarDTO;

/**
 * @method getName(): string
 * @method getAge(): int
 */
final class MyDTO extends AbstractScalarDTO
{
    /** 
     * @var string 
     */
    private $name;

    /**
     * @var int
     */
    private $age;

    /**
     * @var string 
     */
    private $date;

    /**
     * Custom named constructor.
     *
     * @param string            $name
     * @param int               $age
     * @param DateTimeImmutable $date
     * 
     * @return self
     */
    public static function instantiate(
        string $name,
        int $age,
        \DateTimeImmutable $date
    ): self {
        return new static([
            'name' => $name,
            'age' => $age,
            'date' => $date->format(\DateTime::ATOM), // Transform to scalar
        ]);
    }

    /**
     * Transform back to original.
     * 
     * @retrun \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(\DateTime::ATOM, $this->date);
    }
}

$myDto = MyDTO::instantiate('name', 24, new \DateTimeImmutable('now'));

$name = $myDto->get('name'); // Same as $myDto->getName();
$age = $myDto->get('age'); // Same as $myDto->getAge();
$date = $myDto->get('date'); // Same as$myDto->getDate();
```

Access to DTO attributes can be done in three different ways

* By using the "get" method
* By defining a getter method in the form "getAttribute()" (the only allowed public methods). Great for transforming values if you are using AbstractScalarDTO as shown in the example above
* Or by the magic method "__call" in the form "getAttribute()"". If you plan to use this feature it's best to annotate the magic getters at class level with `@method` phpDoc tag; this will help your IDE auto-completion

### Collections
Finally `Gears\DTO\AbstractDTOCollection` is a special type of DTO that only accepts a list of elements, being these elements implementations of DTO interface itself. This object is meant to be used as a return value when several DTOs should be returned, for example from a DDBB query result

### Standalone usage

Alternatively you can use PayloadBehaviour trait in any object you want to have immutable DTO functionality

```php
use Gears\DTO\DTO;
use Gears\DTO\PayloadBehaviour;

final class MyDTO implements DTO
{
    use PayloadBehaviour;

    private $value;

    public function __construct(int $value)
    {
        $this->setPayload(['value' => $value]);
    }

    final public function getAllowedInterfaces(): array
    {
        return [DTO::class];
    }
}
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/dto/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/dto/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/dto/blob/master/LICENSE) included with the source code for a copy of the license terms.
