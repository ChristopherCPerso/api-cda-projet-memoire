<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Companies;
use App\Entity\RestaurantImages;
use App\Entity\RestaurantSchedule;
use App\Entity\Restaurants;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 1) Utilisateurs (5 dont 1 admin demandé)
        $users = [];

        $admin = new User();
        $admin->setFirstname('Christopher');
        $admin->setLastname('Chiarandini');
        $admin->setEmail('cchiarandini@proton.me');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Deathfab85'));
        $admin->setIsAdmin(true);
        $admin->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);
        $users[] = $admin;

        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
            $user->setIsAdmin(false);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $users[] = $user;
        }

        // 2) Récupération des catégories créées par CategorieFixtures
        $categories = $manager->getRepository(Categories::class)->findAll();

        // 3) 30 Restaurants avec images, horaires et catégories
        $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];

        for ($i = 0; $i < 30; $i++) {
            $restaurant = new Restaurants();
            $restaurant->setName($faker->company());
            $restaurant->setAddress($faker->streetAddress());
            $restaurant->setPostalCode((int) $faker->postcode());
            $restaurant->setCity($faker->city());
            $restaurant->setDescription($faker->optional()->sentence(12));
            $restaurant->setCreatedAt(new \DateTimeImmutable());
            $restaurant->setUser($faker->randomElement($users));

            // 1 à 4 catégories
            if (!empty($categories)) {
                $randomCategories = $faker->randomElements($categories, $faker->numberBetween(1, 4));
                foreach ($randomCategories as $category) {
                    $restaurant->addCategory($category);
                }
            }

            // 2 à 5 images picsum
            $numImages = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $numImages; $j++) {
                $image = new RestaurantImages();
                $random = random_int(1, 1000000);
                $image->setLink('https://picsum.photos/600/600?random=' . $random);
                $image->setRestaurant($restaurant);
                $manager->persist($image);
            }

            // Horaires: 2 créneaux par jour sauf un jour fermé
            $closedDay = $faker->randomElement($days);
            foreach ($days as $day) {
                if ($day === $closedDay) {
                    $scheduleClosed = new RestaurantSchedule();
                    $scheduleClosed->setDaysOfWeek($day);
                    $scheduleClosed->setOpenTime(\DateTime::createFromFormat('H:i', '00:00'));
                    $scheduleClosed->setCloseTime(\DateTime::createFromFormat('H:i', '00:00'));
                    $scheduleClosed->setIsClosed(true);
                    $restaurant->addOpeningHour($scheduleClosed);
                } else {
                    $scheduleLunch = new RestaurantSchedule();
                    $scheduleLunch->setDaysOfWeek($day);
                    $scheduleLunch->setOpenTime(\DateTime::createFromFormat('H:i', '11:30'));
                    $scheduleLunch->setCloseTime(\DateTime::createFromFormat('H:i', '14:00'));
                    $scheduleLunch->setIsClosed(false);
                    $restaurant->addOpeningHour($scheduleLunch);

                    $scheduleDinner = new RestaurantSchedule();
                    $scheduleDinner->setDaysOfWeek($day);
                    $scheduleDinner->setOpenTime(\DateTime::createFromFormat('H:i', '19:00'));
                    $scheduleDinner->setCloseTime(\DateTime::createFromFormat('H:i', '00:00'));
                    $scheduleDinner->setIsClosed(false);
                    $restaurant->addOpeningHour($scheduleDinner);
                }
            }

            $manager->persist($restaurant);

            // Reviews 1 à 5 par restaurant
            $numReviews = $faker->numberBetween(1, 5);
            for ($k = 0; $k < $numReviews; $k++) {
                $review = new Review();
                $review->setRating($faker->numberBetween(0, 5));
                $review->setComment($faker->optional()->sentence(15));
                $review->setCreatedAt(new \DateTimeImmutable());
                $review->setRestaurant($restaurant);
                $review->setAuthor($faker->randomElement($users));
                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategorieFixtures::class];
    }
}
