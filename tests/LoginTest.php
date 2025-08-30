<?php

namespace App\Test;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginSuccessfully(): void
    {
        $client = static::createClient();

        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'bdecajou1@gmail.com']);

        $client->request('POST', '/api/login_check', 
        [], 
        [], 
        ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $user->getEmail(),
            'password' => 'jeSu1sleP4ssW0rdsUp3RrBu5te'
        ]));

        $this->assertResponseStatusCodeSame(200);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $responseData);
        $this->assertNotEmpty($responseData['token']);
        $this->assertArrayNotHasKey('password', $responseData);
        $this->assertArrayNotHasKey('plainPassword', $responseData);

        //retour visuel
        $statusCode = $client->getResponse()->getStatusCode();
        $statusContent = $client->getResponse()->getContent();
        
        echo "\n***Résultat obtenu pour la connexion***\n";
        echo "Code HTTP: $statusCode\n";
        echo "Response: $statusContent\n";
    
    }
}