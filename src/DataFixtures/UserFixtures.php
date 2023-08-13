<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AppFixtures implements FixtureGroupInterface
{
    public const USER_NBR = 50;
    public const USER_REF = 'user_';
    public const USER_DEV_ID = 1;

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public static function getGroups(): array
    {
        return [];
    }

    public function loadFixtures(ObjectManager $manager): void
    {
        $this->createUsers($manager);

        $manager->flush();
    }

    private function createUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::USER_NBR; ++$i) {
            /** @var User $user */
            $user = self::USER_DEV_ID === $i ? $this->createDevUser() : $this->createRandomUser();

            $pwd = $this->hasher->hashPassword($user, '0000');

            $user
                ->setPassword($pwd)
                ->setRoles(self::USER_DEV_ID === $i ? ['ROLE_ADMIN'] : ['ROLE_USER']);

            $manager->persist($user);

            $this->addReference(
                sprintf('%s%d', self::USER_REF, $i),
                $user
            );
        }
    }

    private function createRandomUser()
    {
        /** @var User $user */
        $user = new User();
        $user
            ->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setEmail($this->faker->email())
        ;

        return $user;
    }

    private function createDevUser()
    {
        /** @var User $user */
        $user = new User();
        $user
            ->setFirstName('Joe')
            ->setLastName('Developer')
            ->setEmail('joe.developer@test.com')
        ;

        return $user;
    }
}
