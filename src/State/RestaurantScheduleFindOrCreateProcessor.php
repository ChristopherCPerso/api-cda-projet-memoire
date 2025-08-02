<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Restaurants;
use App\Entity\RestaurantSchedule;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\Post;

final class RestaurantScheduleFindOrCreateProcessor implements ProcessorInterface
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

        $submittedSchedules = $data->getOpeningHours();

        $managedSchedules = new ArrayCollection();

        $scheduleRepository = $this->entityManager->getRepository(RestaurantSchedule::class);

        foreach ($submittedSchedules as $submittedSchedule) {
            $existingSchedule = $scheduleRepository->findOneBy([
                'daysOfWeek' => $submittedSchedule->getDaysOfWeek(),
                'openTime' => $submittedSchedule->getOpenTime(),
                'closeTime' => $submittedSchedule->getCloseTime(),
                'isClosed' => $submittedSchedule->isClosed(),
            ]);

            if ($existingSchedule) {
                $managedSchedules->add($existingSchedule);
            } else {
                $managedSchedules->add($submittedSchedule);
            }
        }

        $data->getOpeningHours()->clear();

        foreach ($managedSchedules as $managedSchedule) {
            $data->addOpeningHour($managedSchedule);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
