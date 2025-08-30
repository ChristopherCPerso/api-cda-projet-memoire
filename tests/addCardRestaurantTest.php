<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class addCardRestaurantTest extends WebTestCase
{
    public function testAddCardRestaurantSuccessfully(): void
    {
    $client = static::createClient();

    //Récupération du TOKEN
    $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'bdecajou1@gmail.com']);

    $client->request('POST', '/api/login_check',
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode([
        'username' => $user->getEmail(),
        'password' => 'jeSu1sleP4ssW0rdsUp3RrBu5te'
    ]));

    $this->assertResponseStatusCodeSame(200);

    $responseData = json_decode($client->getResponse()->getContent(), true);

    $this->assertArrayHasKey('token', $responseData);

    // Ajout d'une carte de restaurant

    $payload = [
        'name'=> 'Le bon Test',
        'address'=> '42 clos du cossignol',
        'postalCode'=> 31520,
        'city'=> 'Ramonville-Saint-Agne',
        'description'=> 'Un restaurant de test fort sympathique',
        'phone'=> '0505050505',
        'categories'=> [
            ['name' => 'Bistrot']
        ],
        'paymentCategories'=> [
            ['type' => 'Deliveroo']
        ],
        'openingHours'=> [
            ['daysOfWeek' => 'Lundi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Lundi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Mardi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Mardi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Mercredi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Mercredi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Jeudi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Jeudi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Vendredi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Vendredi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Samedi', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Samedi', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
            ['daysOfWeek' => 'Dimanche', 'serviceName' => 'LUNCH', 'isClosed' => false, 'openTime' => '1970-01-01T12:00:00.000Z', 'closeTime' => '1970-01-01T14:00:00.000Z'],
            ['daysOfWeek' => 'Dimanche', 'serviceName' => 'DINNER', 'isClosed' => false, 'openTime' => '1970-01-01T19:00:00.000Z', 'closeTime' => '1970-01-01T22:00:00.000Z'],
        ],
        'restaurantImages'=> []
    ];

    $client->request('POST', '/api/restaurants',
    [],
    [],
    [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_AUTHORIZATION' => 'Bearer ' . $responseData['token']
    ],
    json_encode($payload));

    $this->assertResponseStatusCodeSame(201);

    $responseData = json_decode($client->getResponse()->getContent(), true);

    $this->assertArrayHasKey('id', $responseData);
    $this->assertNotEmpty($responseData['id']);

    //retour visuel
    $statusCode = $client->getResponse()->getStatusCode();
    $statusContent = $client->getResponse()->getContent();
    
    echo "\n*** Résultat obtenu pour l'ajout d'une carte de restaurant ***\n";
    echo "Code HTTP: $statusCode\n";
    echo "Response: $statusContent\n";

}
}