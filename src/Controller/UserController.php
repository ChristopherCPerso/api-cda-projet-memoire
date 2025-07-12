<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/users', name: 'app_users')]
    public function getAllUsers(UsersRepository $users, SerializerInterface $serializer): JsonResponse
    {

        $user = $serializer->serialize($users->findAll(), 'json', ['groups' => 'users']);

        return new JsonResponse(
            $user,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/api/users/{id}', name: 'app_user_by_id')]
    public function getUserById($id, UsersRepository $users, SerializerInterface $serializer): JsonResponse
    {

        $user = $users->findById($id);

        if ($user) {
            $refactUser = $serializer->serialize(
                $user,
                'json',
                ['groups' => 'users']
            );

            return new JsonResponse(
                $refactUser,
                Response::HTTP_OK,
                [],
                true
            );
        }

        return new JsonResponse(
            [
                "Status" => Response::HTTP_NOT_FOUND,
                "Error" => "L'utilisateur n'existe pas"
            ],
            Response::HTTP_NOT_FOUND,

        );
    }
}
