<?php

namespace PUGX\Shortid\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use PUGX\Shortid\Shortid;

final class ShortidGenerator extends AbstractIdGenerator
{
    // ORM 2
    public function generate(EntityManager $manager, $entity): Shortid
    {
        return Shortid::generate();
    }

    // ORM 3
    public function generateId(EntityManagerInterface $em, ?object $entity): Shortid
    {
        return Shortid::generate();
    }
}
