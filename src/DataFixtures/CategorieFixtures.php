<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
        }

        $manager->flush();
    }
}
