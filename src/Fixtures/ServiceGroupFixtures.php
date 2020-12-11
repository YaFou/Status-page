<?php

namespace App\Fixtures;

use App\Entity\ServiceGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceGroupFixtures extends Fixture
{
    private const NUMBER = 10;
    /**
     * @var Generator
     */
    private $faker;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Generator $faker, ContainerInterface $container)
    {
        $this->faker = $faker;
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        dump($this->container->getParameter('doctrine.dbal.connection_factory.types'));
        for ($i = 0; self::NUMBER > $i; $i++) {
            $group = (new ServiceGroup())->setName($this->faker->words(3, true));
            $manager->persist($group);
        }

        $manager->flush();
    }
}
