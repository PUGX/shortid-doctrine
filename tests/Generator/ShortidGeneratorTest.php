<?php

namespace PUGX\Shortid\Doctrine\Test\Generator;

use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Doctrine\Generator\ShortidGenerator;

final class ShortidGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $manager = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $generator = new ShortidGenerator();
        $entity = 'TODO';
        $id = $generator->generate($manager, $entity);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $id);
    }
}
