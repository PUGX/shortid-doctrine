<?php

namespace PUGX\Shortid\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use PUGX\Shortid\Shortid;

class ShortidGenerator extends AbstractIdGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(EntityManager $manager, $entity): Shortid
    {
        return Shortid::generate();
    }
}
