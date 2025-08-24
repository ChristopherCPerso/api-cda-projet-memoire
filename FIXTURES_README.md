# 📚 Guide des Fixtures - API ATECN Advisor

## 🎯 Objectif

Ce document explique comment les fixtures sont configurées pour fonctionner uniquement en environnement de développement, garantissant qu'aucune donnée de test ne sera présente en production.

## 🏗️ Architecture des Fixtures

### 1. **PaymentCategoryFixtures.php**

-   Crée les 7 catégories de paiement demandées
-   Exécuté en premier pour créer les données de référence

### 2. **CategorieFixtures.php**

-   Crée les catégories de restaurants
-   Exécuté en deuxième

### 3. **AppFixtures.php**

-   Crée les utilisateurs, restaurants, images, horaires et avis
-   Dépend des deux fixtures précédentes
-   Assigne aléatoirement 2 à 5 catégories de paiement par restaurant

## 🔒 Sécurité de Production

### Configuration Composer

```json
"require-dev": {
    "doctrine/doctrine-fixtures-bundle": "^4.1"
}
```

-   Les fixtures sont dans `require-dev` → **NE SONT PAS installées en production**

### Configuration Symfony

```php
// config/bundles.php
Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true]
```

-   Le bundle ne s'active qu'en `dev` et `test`

## 🚀 Utilisation

### Développement

```bash
# Charger toutes les fixtures
php bin/console doctrine:fixtures:load --env=dev

# Charger en mode append (sans purger)
php bin/console doctrine:fixtures:load --env=dev --append
```

### Production

```bash
# Les fixtures ne sont pas disponibles
APP_ENV=prod composer install --no-dev
```

## 📊 Données Créées

### Catégories de Paiement

1. **Carte Bancaire**
2. **Espèce**
3. **Tickets Restaurant**
4. **Apple Pay**
5. **Google Pay**
6. **Uber Eats**
7. **Deliveroo**

### Distribution

-   Chaque restaurant reçoit 2 à 5 méthodes de paiement aléatoirement
-   Toutes les méthodes sont utilisées de manière équilibrée

## 🔧 Maintenance

### Ajouter une nouvelle méthode de paiement

1. Modifier `PaymentCategoryFixtures.php`
2. Ajouter le nom dans le tableau `$paymentTypes`
3. Relancer les fixtures

### Modifier la logique d'assignation

1. Modifier `AppFixtures.php` dans la section des catégories de paiement
2. Ajuster la logique `$faker->numberBetween(2, 5)`

## ⚠️ Points d'Attention

-   **NE JAMAIS** exécuter les fixtures en production
-   Les fixtures purgent la base par défaut (utiliser `--append` si nécessaire)
-   Vérifier que `APP_ENV=dev` avant d'exécuter les fixtures
-   Les données sont générées avec Faker pour la variété
