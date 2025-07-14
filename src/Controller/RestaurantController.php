<?php

namespace App\Controller;

use App\Entity\Restaurants;
use App\Repository\RestaurantsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RestaurantController extends AbstractController
{
    #[Route('api/restaurants', name: 'app_add_restaurant', methods: ['POST'])]
    public function addRestaurant(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        $restaurant = $serializer->deserialize($request->getContent(), Restaurants::class, 'json');

        $content = $request->toArray();
        $idUser = $content['user'] ?? null;

        $restaurant->setUser($userRepository->find($idUser));

        //Validation des données avec Validator
        $errors = $validator->validate($restaurant);
        if ($errors->count() > 0) {
            foreach ($errors as $violation) {
                return new JsonResponse(
                    [
                        "status" => Response::HTTP_BAD_REQUEST,
                        "Message" => $violation->getMessage(),
                        "Property" => $violation->getPropertyPath()
                    ],
                    Response::HTTP_BAD_REQUEST,
                );
            }
        }

        $em->persist($restaurant);
        $em->flush();

        $jsonRestaurant = $serializer->serialize($restaurant, 'json', ['groups' => 'getRestaurants']);
        return new JsonResponse(
            $jsonRestaurant,
            Response::HTTP_OK,
            [],
            true
        );
    }


    #[Route('/api/restaurants/{id}', name: 'app_restaurant_by_id', methods: ['GET'])]
    public function getRestaurantById($id, RestaurantsRepository $restaurantsRepository, SerializerInterface $serializer): JsonResponse
    {
        $restaurant = $restaurantsRepository->findById($id);

        if ($restaurant) {
            $jsonRestaurant = $serializer->serialize(
                $restaurant,
                'json',
                ['groups' => 'getRestaurants']
            );
            return new JsonResponse(
                $jsonRestaurant,
                Response::HTTP_OK,
                [],
                true
            );
        }
        return new JsonResponse(
            [
                "Status" => Response::HTTP_NOT_FOUND,
                "Error" => "Le restaurant n'existe pas"
            ],
            Response::HTTP_NOT_FOUND,
        );
    }
    #[Route('/api/restaurants', name: 'app_restaurant', methods: ['GET'])]
    public function getAllRestaurants(RestaurantsRepository $restaurantsRepository, SerializerInterface $serializer): JsonResponse
    {
        $restaurants = $restaurantsRepository->findAll();
        $jsonRestaurants = $serializer->serialize(
            $restaurants,
            'json',
            ['groups' => 'getRestaurants']
        );

        return new JsonResponse(
            $jsonRestaurants,
            Response::HTTP_OK,
            [],
            true
        );
    }


    #[Route('api/restaurants/{id}', name: 'app_update_restaurant', methods: ['PUT'])]
    public function updateRestaurant(
        Request $request,
        SerializerInterface $serializer,
        Restaurants $currentRestaurant,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ): JsonResponse {

        $updateRestaurant = $serializer->deserialize(
            $request->getContent(),
            Restaurants::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentRestaurant]
        );

        $content = $request->toArray();
        $idUser = $content['user'] ?? null;

        $updateRestaurant->setUser($userRepository->find($idUser));

        $em->persist($updateRestaurant);
        $em->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    #[Route('/api/restaurants/{id}', name: 'app_delete_restaurant_by_id', methods: ['DELETE'])]
    public function deleteRestaurantById(Restaurants $restaurant, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($restaurant);
        $em->flush();
        return new JsonResponse(
            [
                "Status" => Response::HTTP_NO_CONTENT,
                "Message" => "Le restaurant a été supprimé de la base de donnée"
            ],
        );
    }
}
