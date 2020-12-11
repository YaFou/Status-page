<?php

namespace App\Tests\Entity;

use App\Entity\ServiceGroup;
use App\Tests\TestUtilTrait;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceGroupTest extends KernelTestCase
{
    use TestUtilTrait;

    /**
     * @param ServiceGroup $serviceGroup
     * @param int $numberOfErrors
     * @dataProvider provideEntitiesToTest
     */
    public function testValidate(ServiceGroup $serviceGroup, int $numberOfErrors)
    {
        self::bootKernel();
        $validator = $this->get('validator');
        $this->assertCount($numberOfErrors, $validator->validate($serviceGroup));
    }

    public function provideEntitiesToTest(): Generator
    {
        yield 'name blank' => [new ServiceGroup(), 1];

        yield 'valid' => [
            (new ServiceGroup())->setName('name'),
            0
        ];
    }
}
