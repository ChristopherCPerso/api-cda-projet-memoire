<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class addCardFailedRestaurantTest extends WebTestCase
{
    public function testAddCardRestaurantFailedTest(): void
    {
    $client = static::createClient();

    // Ajout d'une carte de restaurant

    $payload = [
        'id'=> 1,
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
        'HTTP_AUTHORIZATION' => 'Bearer j3n35u15p45c0nn3v73' //TOKEN ERRONE 
    ],
    json_encode($payload));

    $this->assertResponseStatusCodeSame(401);

    $responseData = json_decode($client->getResponse()->getContent(), true);

    $this->assertArrayHasKey('message', $responseData);
    $this->assertNotEmpty($responseData['message']);

    //retour visuel
    $statusCode = $client->getResponse()->getStatusCode();
    $statusContent = $client->getResponse()->getContent();
    
    echo "\n*** Résultat obtenu pour l'ajout d'une carte de restaurant ***\n";
    echo "Code HTTP: $statusCode\n";
    echo "Response: $statusContent\n";

}
}