<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController
{
    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): Response
    {
        $response = new Response();


        $cookie = Cookie::create('JWT', '')
            ->withExpires(new \DateTime('-1 day')) // date passée pour le supprimer
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(false) // mettre true en prod avec HTTPS
            ->withSameSite('Strict');

        $response->headers->setCookie($cookie);

        // On peut renvoyer un JSON simple
        $response->setContent(json_encode(['message' => 'Logged out']));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
