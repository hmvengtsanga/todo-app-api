<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class AppFixtures extends Fixture
{
    protected Generator $faker;

    abstract protected function loadFixtures(ObjectManager $manager);

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');

        $this->loadFixtures($manager);
    }
}
