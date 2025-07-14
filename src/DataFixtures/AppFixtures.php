<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Restaurants;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $uph)
    {
        $this->userPasswordHasher = $uph;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $admin = new User;
        $admin->setFirstname("Christopher");
        $admin->setLastname("Chiarandini");
        $admin->setEmail("admin@admin.fr");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "Deathfab85"));
        $admin->setIsAdmin(0);
        $admin->setCreatedAt(new \DateTimeImmutable());
        //$admin->setupdatedAt(new \DateTimeImmutable());
        $manager->persist($admin);
        $listUser[] = $admin;

        for ($i = 0; $i < 10; $i++) {
            $user = new User;
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "user"));
            $user->setIsAdmin(0);
            $user->setCreatedAt(new \DateTimeImmutable());
            //$user->setupdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $listUser[] = $user;
        }

        $categories = [
            // Cuisine française
            'Gastronomique',
            'Bistrot',
            'Brasserie',
            'Crêperie',
            'Bouillon',
            'Auberge',
            'Cuisine du terroir',
            'Cuisine traditionnelle française',

            // Cuisine italienne
            'Pizzeria',
            'Trattoria',
            'Osteria',
            'Pasta & Risotto',
            'Gastronomie italienne',

            // Cuisine asiatique
            'Japonais',
            'Chinois',
            'Thaïlandais',
            'Vietnamien',
            'Coréen',
            'Cambodgien',
            'Fusion asiatique',

            // Cuisine Amérique latine
            'Mexicain / Tex-Mex',
            'Péruvien',
            'Brésilien',
            'Argentin',
            'Latino fusion',

            // Méditerranéenne & orientale
            'Espagnol / Tapas',
            'Portugais',
            'Grec',
            'Turc',
            'Libanais',
            'Marocain',
            'Tunisien',
            'Algérien',
            'Israélien',

            // Street food / fast food
            'Fast food',
            'Burger',
            'Tacos',
            'Kebabs',
            'Sandwiches',
            'Hot-dogs',
            'Friterie',
            'Food truck',

            // Végétarien / Healthy
            'Végétarien',
            'Vegan',
            'Bio',
            'Sans gluten',
            'Cuisine santé',
            'Juice bar',
            'Raw food',

            // Autres cuisines
            'Indien / Pakistanais',
            'Créole / Antillais / Réunionnais',
            'Africain',
            'Russe / Géorgien',
            'Scandinave',
            'Anglo-saxon',

            // Types de service
            'Buffet à volonté',
            'À emporter',
            'Livraison uniquement',
            'Restaurant familial',
            'Restaurant romantique',
            'Dîner-spectacle',
            'Rooftop',
            'Pop-up / Éphémère',
            'Café / Salon de thé',
            'Bar à vin',
            'Bar à cocktails',
            'Bar à bières',
            'Cantine / Self',
        ];

        foreach ($categories as $name) {
            $categorie = new Categories();
            $categorie->setName($name);
            $manager->persist($categorie);
            $listCategory[] = $categorie;
        }

        for ($i = 0; $i < 10; $i++) {
            $restaurant = new Restaurants;
            $restaurant->setName($faker->company());
            $restaurant->setAddress($faker->streetAddress());
            $restaurant->setPostalCode($faker->postcode());
            $restaurant->setCity("Toulouse");
            $restaurant->setCreatedAt(new \DateTimeImmutable());
            $restaurant->setUser($listUser[array_rand($listUser)]);
            $randomCategories = $faker->randomElements($listCategory, rand(1, 3));
            foreach ($randomCategories as $category) {
                $restaurant->addCategory($category);
            }
            //$restaurant->setupdatedAt(new \DateTimeImmutable());
            $manager->persist($restaurant);
        }

        $manager->flush();
    }
}
