<?php

namespace PUGX\Shortid\Doctrine\Test\Generator;

use PHPUnit_Framework_TestCase as TestCase;
use PUGX\Shortid\Doctrine\Generator\ShortidGenerator;

class ShortidGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $manager = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $generator = new ShortidGenerator();
        $entity = 'TODO';
        $id = $generator->generate($manager, $entity);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $id);
    }
}
