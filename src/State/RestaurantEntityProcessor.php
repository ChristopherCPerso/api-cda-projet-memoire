<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Restaurants;
use App\Entity\RestaurantSchedule;
use App\Entity\PaymentCategory;
use App\Entity\Categories;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\Post;

final class RestaurantEntityProcessor implements ProcessorInterface
{
    public function __construct(
        // Le processeur qui sera appelé après le nôtre (celui de Doctrine par défaut)
        private readonly ProcessorInterface $persistProcessor,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param mixed $data L'objet désérialisé (notre entité Restaurants)
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof Restaurants || !$operation instanceof Post) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        // Gestion des horaires d'ouverture
        $this->processSchedules($data);
        
        // Gestion des types de paiement
        $this->processPaymentCategories($data);
        
        // Gestion des catégories
        $this->processCategories($data);

        // Laisser le processeur Doctrine gérer la persistance
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    /**
     * Traite les horaires d'ouverture pour éviter la duplication
     */
    private function processSchedules(Restaurants $restaurant): void
    {
        $submittedSchedules = $restaurant->getOpeningHours();
        
        // Vérifier si des horaires ont été soumis
        if ($submittedSchedules === null || $submittedSchedules->isEmpty()) {
            return;
        }

        $managedSchedules = new ArrayCollection();
        $scheduleRepository = $this->entityManager->getRepository(RestaurantSchedule::class);

        foreach ($submittedSchedules as $submittedSchedule) {
            // Vérifier que l'horaire soumis a les données nécessaires
            if (!$submittedSchedule->getDaysOfWeek() || !$submittedSchedule->getOpenTime() || !$submittedSchedule->getCloseTime()) {
                continue;
            }

            // Rechercher un horaire existant avec les mêmes critères
            $existingSchedule = $scheduleRepository->findOneBy([
                'daysOfWeek' => $submittedSchedule->getDaysOfWeek(),
                'openTime' => $submittedSchedule->getOpenTime(),
                'closeTime' => $submittedSchedule->getCloseTime(),
                'isClosed' => $submittedSchedule->getIsClosed(),
            ]);

            if ($existingSchedule) {
                // Utiliser l'horaire existant
                $managedSchedules->add($existingSchedule);
            } else {
                // Garder le nouvel horaire tel quel (il sera persisté par le processeur Doctrine)
                $managedSchedules->add($submittedSchedule);
            }
        }

        // Remplacer la collection d'horaires par les horaires gérés
        $restaurant->getOpeningHours()->clear();
        foreach ($managedSchedules as $managedSchedule) {
            $restaurant->addOpeningHour($managedSchedule);
        }
    }

    /**
     * Traite les types de paiement pour éviter la duplication
     */
    private function processPaymentCategories(Restaurants $restaurant): void
    {
        $submittedPaymentCategories = $restaurant->getPaymentCategories();
        
        // Vérifier si des types de paiement ont été soumis
        if ($submittedPaymentCategories === null || $submittedPaymentCategories->isEmpty()) {
            return;
        }

        $managedPaymentCategories = new ArrayCollection();
        $paymentCategoryRepository = $this->entityManager->getRepository(PaymentCategory::class);

        foreach ($submittedPaymentCategories as $submittedPaymentCategory) {
            // Vérifier que le type de paiement soumis a les données nécessaires
            if (!$submittedPaymentCategory->getType()) {
                continue;
            }

            // Rechercher un type de paiement existant avec le même type
            $existingPaymentCategory = $paymentCategoryRepository->findOneBy([
                'type' => $submittedPaymentCategory->getType(),
            ]);

            if ($existingPaymentCategory) {
                // Utiliser le type de paiement existant
                $managedPaymentCategories->add($existingPaymentCategory);
            } else {
                // Garder le nouveau type de paiement tel quel (il sera persisté par le processeur Doctrine)
                $managedPaymentCategories->add($submittedPaymentCategory);
            }
        }

        // Remplacer la collection de types de paiement par ceux gérés
        $restaurant->getPaymentCategories()->clear();
        foreach ($managedPaymentCategories as $managedPaymentCategory) {
            $restaurant->addPaymentCategory($managedPaymentCategory);
        }
    }

    /**
     * Traite les catégories pour éviter la duplication
     */
    private function processCategories(Restaurants $restaurant): void
    {
        $submittedCategories = $restaurant->getCategories();
        
        // Vérifier si des catégories ont été soumises
        if ($submittedCategories === null || $submittedCategories->isEmpty()) {
            return;
        }

        $managedCategories = new ArrayCollection();
        $categoryRepository = $this->entityManager->getRepository(Categories::class);

        foreach ($submittedCategories as $submittedCategory) {
            // Vérifier que la catégorie soumise a les données nécessaires
            if (!$submittedCategory->getName()) {
                continue;
            }

            // Rechercher une catégorie existante avec le même nom
            $existingCategory = $categoryRepository->findOneBy([
                'name' => $submittedCategory->getName(),
            ]);

            if ($existingCategory) {
                // Utiliser la catégorie existante
                $managedCategories->add($existingCategory);
            } else {
                // Garder la nouvelle catégorie tel quel (elle sera persistée par le processeur Doctrine)
                $managedCategories->add($submittedCategory);
            }
        }

        // Remplacer la collection de catégories par celles gérées
        $restaurant->getCategories()->clear();
        foreach ($managedCategories as $managedCategory) {
            $restaurant->addCategory($managedCategory);
        }
    }
}
