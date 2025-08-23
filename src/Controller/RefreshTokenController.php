<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;

#[Route('/api')]
class RefreshTokenController extends AbstractController
{
    public function __construct(
        private RefreshTokenManagerInterface $refreshTokenManager,
        private JWTTokenManagerInterface $jwtManager,
        private UserRepository $userRepository
    ) {}

    #[Route('/token/refresh', name: 'api_refresh_token', methods: ['POST'])]
    public function refresh(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $refreshToken = $data['refresh_token'] ?? null;

        if (!$refreshToken) {
            return $this->json(['error' => 'refresh_token is required'], 400);
        }

        // Vérifier si le refresh token existe et est valide
        $token = $this->refreshTokenManager->get($refreshToken);

        if (!$token) {
            return $this->json(['error' => 'Invalid refresh token'], 401);
        }

        // Vérifier si le token est expiré
        if ($token->getValid() < new \DateTime()) {
            return $this->json(['error' => 'Expired refresh token'], 401);
        }

        // Récupérer l'utilisateur et générer un nouveau JWT
        $username = $token->getUsername();
        if (!$username) {
            return $this->json(['error' => 'Invalid user in refresh token'], 401);
        }

        // Récupérer l'utilisateur complet
        $user = $this->userRepository->findOneBy(['email' => $username]);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 401);
        }

        $newJwt = $this->jwtManager->create($user);

        return $this->json([
            'token' => $newJwt,
            'refresh_token' => $refreshToken
        ]);
    }
}
