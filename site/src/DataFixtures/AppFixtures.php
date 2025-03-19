<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasherInterface)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $user = null;
        
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->userName());
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $faker->password(6, 20)));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
