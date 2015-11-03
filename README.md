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

To configure Doctrine to use pugx/shortid as a field type, you'll need to set up
the following in your bootstrap:

``` php
\Doctrine\DBAL\Types\Type::addType('shortid', 'PUGX\ShortIdd\Doctrine\ShortIdType');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('shortid', 'shortid');
```

Then, in your models, you may annotate properties by setting the `@Column`
type to `shortid`. Since databases are not be able to
auto-generate a ShortId when inserting into the database,
in your model's constructor (or elsewhere, depending on how you create instances
of your model), generate a `PUGX\Shortid\Shortid` object for the property. Doctrine
will handle the rest.

For example, here we annotate an `@Id` column with the `shortid` type, and in the
constructor, we generate a ShortId to store for this entity.

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
