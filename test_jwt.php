<?php

require_once 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

// URL de votre API
$baseUrl = 'http://localhost:8000'; // Ajustez selon votre configuration

echo "=== Test JWT avec ID utilisateur ===\n";
echo "Base URL: $baseUrl\n\n";

// Étape 1: Tenter de se connecter
echo "1. Tentative de connexion...\n";
$loginData = [
    'email' => 'test@example.com', // Remplacez par un email valide
    'password' => 'password123'     // Remplacez par un mot de passe valide
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/login_check');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "Réponse: $response\n\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['token'])) {
        echo "2. JWT reçu avec succès!\n";
        
        // Décoder le JWT pour voir le payload
        $tokenParts = explode('.', $data['token']);
        if (count($tokenParts) === 3) {
            $payload = json_decode(base64_decode($tokenParts[1]), true);
            echo "Payload JWT décodé:\n";
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            echo "\n\n";
            
            // Vérifier si l'ID utilisateur est présent
            if (isset($payload['id'])) {
                echo "✅ SUCCÈS: L'ID utilisateur ($payload[id]) est présent dans le JWT!\n";
            } else {
                echo "❌ ÉCHEC: L'ID utilisateur n'est PAS présent dans le JWT\n";
            }
            
            if (isset($payload['firstname'])) {
                echo "✅ Le prénom ($payload[firstname]) est présent\n";
            }
            
            if (isset($payload['lastname'])) {
                echo "✅ Le nom ($payload[lastname]) est présent\n";
            }
            
            if (isset($payload['isAdmin'])) {
                echo "✅ Le statut admin ($payload[isAdmin]) est présent\n";
            }
        }
    }
} else {
    echo "❌ Échec de la connexion. Vérifiez vos identifiants et que l'API est en cours d'exécution.\n";
}

echo "\n=== Fin du test ===\n";
