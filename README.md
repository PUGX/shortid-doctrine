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
\Doctrine\DBAL\Types\Type::addType('shortid', 'PUGX\ShortIdd\Doctrine\ShortIdType');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('shortid', 'shortid');
```

Then, in your entities, you may annotate properties by setting the `@Column`
type to `shortid`.

You can generate a `PUGX\Shortid\Shortid` object for the property in your constructor, or
use the built-in genrator.

Example with shortid created manually in constructor:

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
