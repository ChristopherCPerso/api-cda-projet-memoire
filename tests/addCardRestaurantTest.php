<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddCardRestaurantTest extends WebTestCase
{
    public function testAddCardRestaurantSuccessfully(): void
    {
        $client = static::createClient();

        // Récupération du TOKEN
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'cchiarandini@proton.me']);

        $client->request('POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $user->getEmail(),
            'password' => 'Deathfab85'
        ]));

        $this->assertResponseStatusCodeSame(200);

        $loginData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $loginData);

        // Ajout d'une carte de restaurant
        $payload = [
            'name' => 'Le bon Test',
            'address' => '42 clos du cossignol',
            'postalCode' => 31520,
            'city' => 'Ramonville-Saint-Agne',
            'description' => 'Un restaurant de test fort sympathique',
            'phone' => '0505050505',
            'categories' => [['name' => 'Bistrot']],
            'paymentCategories' => [['type' => 'Deliveroo']],
            'openingHours' => [
                ['daysOfWeek' => 'Lundi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z']
            ],
            'restaurantImages' => []
        ];

        $client->request('POST', '/api/restaurants', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $loginData['token']
        ], json_encode($payload));

        $this->assertResponseStatusCodeSame(201);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertNotEmpty($responseData['id']);

        // Retour visuel
        echo "\n*** Résultat obtenu pour l'ajout d'une carte de restaurant ***\n";
        echo "Code HTTP: " . $client->getResponse()->getStatusCode() . "\n";
        echo "Response: " . $client->getResponse()->getContent() . "\n";
    }
}
