<?php

namespace App\DataFixtures;

use App\Entity\PaymentCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $paymentTypes = [
            'Carte Bancaire',
            'Espèce',
            'Tickets Restaurant',
            'Apple Pay',
            'Google Pay',
            'Uber Eats',
            'Deliveroo'
        ];

        foreach ($paymentTypes as $type) {
            $paymentCategory = new PaymentCategory();
            $paymentCategory->setType($type);
            $manager->persist($paymentCategory);
        }

        $manager->flush();
    }
}
