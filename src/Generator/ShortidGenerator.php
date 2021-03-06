<?php

namespace PUGX\Shortid\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use PUGX\Shortid\Shortid;

final class ShortidGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $manager, $entity): Shortid
    {
        return Shortid::generate();
    }
}
