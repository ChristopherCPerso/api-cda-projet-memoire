<?php

namespace App\DataFixtures;

use App\Entity\Restaurants;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User;
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setIsAdmin(0);
            $user->setCreatedAt(new \DateTimeImmutable());
            //$user->setupdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $listUser[] = $user;
        }

        for ($i = 0; $i < 10; $i++) {
            $restaurant = new Restaurants;
            $restaurant->setName($faker->company());
            $restaurant->setAddress($faker->streetAddress());
            $restaurant->setPostalCode($faker->postcode());
            $restaurant->setCity("Toulouse");
            $restaurant->setCreatedAt(new \DateTimeImmutable());
            $restaurant->setUser($listUser[array_rand($listUser)]);
            //$restaurant->setupdatedAt(new \DateTimeImmutable());
            $manager->persist($restaurant);
        }

        $manager->flush();
    }
}
