<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Entity\Companies;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User, User|void>
 */
final readonly class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private CompaniesRepository $companiesRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        // Gestion du mot de passe
        if ($data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            );
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }

        // Gestion de la société (company)
        $company = $data->getCompany();
        if ($company instanceof Companies) {
            // Recherche d'une société existante par nom (et domaine si fourni)
            $criteria = ['name' => $company->getName()];
            if ($company->getDomain()) {
                $criteria['domain'] = $company->getDomain();
            }
            $existingCompany = $this->companiesRepository->findOneBy($criteria);
            if ($existingCompany) {
                $data->setCompany($existingCompany);
            } else {
                // On persiste la nouvelle société
                $this->entityManager->persist($company);
            }
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
