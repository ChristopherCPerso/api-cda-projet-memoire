<?php

namespace App\Controller;

use App\Repository\RestaurantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class RestaurantController extends AbstractController
{
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
}
