<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\Date;

class RegisterTest extends WebTestCase
{
    public function testRegisterUserSuccessfully(): void
    {
        $client = static::createClient();
        
        //Ajout des données de Test
        $payload = [
            'firstname' => 'Benoit',
            'lastname' => 'De Cajou',
            'email' => sprintf('user_%s@example.com', uniqid()),
            'plainPassword' => 'jeSu1sleP4ssW0rdsUp3RrBu5te',
            'isAdmin' => false,
            'roles' => ['ROLE_USER'],
            'createdAt' => (new \DateTime())->format(DATE_ATOM),
            'updateAt' => (new \DateTime())->format(DATE_ATOM),
            'company' => [
                'name' => 'testCorp'
                ]
            ];
            
            //Envoi de la requête POST
            $client->request(
                'POST',
                '/api/users',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($payload)
            );
            
            
            //Initialisation du code attendu 
            $this->assertResponseStatusCodeSame(201);
            
            // Récupération de décodage de la réponse JSON
            $responseData = json_decode($client->getResponse()->getContent(), true);
            

        //Vérification que l'email de retour soit le même que celui envoyé
        $this->assertEquals($payload['email'], $responseData['email']);

        //Vérifie que le mot de passe n'est pas dans le retour
        $this->assertArrayNotHasKey('password', $responseData);

        //Vérification que l'utilisateur a bien été ajouté en BDD
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => $payload['email']]);
        $this->assertNotNull($user, "L'utilisateur devrait exister en BDD après l'inscription");

        //retour visuel
        $statusCode = $client->getResponse()->getStatusCode();
            $statusContent = $client->getResponse()->getContent();
            
            echo "\n*** Résultat obtenu pour l'inscription ***\n";
            echo "Code HTTP: $statusCode\n";
            echo "Response: $statusContent\n";
    }
}
