<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TodoFixtures extends AppFixtures implements FixtureGroupInterface, DependentFixtureInterface
{
    public const TODO_NBR = 50;

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return [];
    }

    public function loadFixtures(ObjectManager $manager): void
    {
        $this->createTodos($manager);

        $manager->flush();
    }

    private function createTodos(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::TODO_NBR; ++$i) {
            /** @var Todo $todo */
            $todo = new Todo();

            $todo
                ->setTitle($this->faker->sentence())
                ->setDescription($this->faker->text())
            ;

            if (0 === $i % 2) {
                $todo->setStatus(StatusEnum::DONE);
            }

            if (0 === $i % 3) {
                $todo->setPublic(true);
            }

            /** @var User $owner */
            $owner = $this->getReference(
                $i <= 10 ? UserFixtures::USER_REF.UserFixtures::USER_DEV_ID : UserFixtures::USER_REF.rand(2, UserFixtures::USER_NBR)
            );
            $todo->setOwner($owner);

            $manager->persist($todo);
        }
    }
}
