ShortId Doctrine Type
=====================

[![Total Downloads](https://poser.pugx.org/pugx/shortid-doctrine/downloads.png)](https://packagist.org/packages/pugx/shortid-doctrine)

A [Doctrine field type](http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html) for
[ShortId](https://github.com/pugx/shortid-php) for PHP.

## Installation

Run the following command:

```bash
composer require pugx/shortid-doctrine
```

## Examples

To configure Doctrine to use ``shortid`` as a field type, you'll need to set up
the following in your bootstrap:

``` php
<?php

\Doctrine\DBAL\Types\Type::addType('shortid', 'PUGX\Shortid\Doctrine\ShortidType');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('shortid', 'shortid');
```

Then, in your entities, you may annotate properties by setting the `@Column`
type to `shortid`.

You can generate a `PUGX\Shortid\Shortid` object for the property in your constructor, or
use the built-in generator.

Example with ShortId created manually in constructor:

``` php
<?php

use PUGX\Shortid\Shortid;

/**
 * @Entity
 * @Table
 */
class Product
{
    /**
     * @var string
     *
     * @Id
     * @Column(type="shortid")
     * @GeneratedValue(strategy="NONE")
     */
    protected $id;

    public function __construct()
    {
        $this->id = Shortid::generate();
    }

    public function getId()
    {
        return $this->id;
    }
}
```

Example with auto-generated shortid:

``` php
<?php

/**
 * @Entity
 * @Table
 */
class Product
{
    /**
     * @var string
     *
     * @Id
     * @Column(type="shortid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="PUGX\ShortidDoctrine\Generator\ShortidGenerator")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
```

If you want to customize ShortId length, you can use the ``length`` option in the Column annotation. Example:

``` php
<?php

use PUGX\Shortid\Shortid;

/**
 * @Entity
 * @Table
 */
class Product
{
    /**
     * @var string
     *
     * @Id
     * @Column(type="shortid", length=5)
     * @GeneratedValue(strategy="NONE")
     */
    protected $id;

    public function __construct()
    {
        $this->id = Shortid::generate(5);
    }
}
```

If you want to customize alphabet and/or to use the built-in generator, you need to setup ShortId in your bootstrap:

``` php
<?php

\Doctrine\DBAL\Types\Type::addType('shortid', 'PUGX\Shortid\Doctrine\ShortidType');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('shortid', 'shortid');

$factory = new \PUGX\Shortid\Factory();
$factory->setAlphabet('é123456789àbcdefghìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.!@|');    // must be 64 characters long
$factory->setLength(5);    // accepts values between 2 and 20
PUGX\Shortid\Shortid::setFactory($factory);
```

Then, you must pay attention to configure every ShortId property with the **same length** (``5`` in this example).
