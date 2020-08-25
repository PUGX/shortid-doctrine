<?php

namespace PUGX\Shortid\Doctrine\Test\Generator;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Doctrine\Generator\ShortidGenerator;
use PUGX\Shortid\Shortid;

final class ShortidGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        /** @var EntityManager $manager */
        $manager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $generator = new ShortidGenerator();
        $entity = new \stdClass();
        $id = $generator->generate($manager, $entity);
        self::assertInstanceOf(Shortid::class, $id);
    }
}
