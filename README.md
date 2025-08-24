# 🚀 Installation du projet Symfony

Bienvenue dans ce projet Symfony !  
Voici les étapes simples et efficaces pour installer l'application en local avec Docker + Composer.

---

## ✅ Prérequis

-   PHP >= 8.1 installé localement
-   Composer
-   Docker & Docker Compose
-   Symfony CLI (optionnel mais recommandé)

---

## ⚙️ Installation pas à pas

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd <nom-du-projet>
```

Git clone

Composer install

Création du docker compose
lancer un docker compose up

Modifier le .env pour connecter la BDD

pour creer les tables dans la BDD
doctrine:database:create
Lancer console doctrine:migrations:migrate

Ajouter des jeux de données (uniquement en développement)

```bash
# Les fixtures ne s'exécutent qu'en environnement dev
symfony console doctrine:fixtures:load --env=dev
```

Lancer symfony avec symfony server start

```

```

##Creation des cles pour JWT

Lancer la commnde pour creer le token privée

```bash
openssl genpkey \ -algorithm RSA \ -out config/jwt/private.pem \ -aes-256-cbc \ -pkeyopt rsa_keygen_bits:4096
```

Lancer la commnde pour creer le token public

```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

🚨 Veillez à mettre des passphrase robuste
⚠️ Il vous faudra indiquer votre passe phrase dans le fichier .env.local

---

## 🌍 Configuration des environnements

### Développement (dev)

-   Les fixtures sont automatiquement disponibles
-   Base de données locale avec données de test
-   Mode debug activé

### Production (prod)

-   Les fixtures ne sont PAS installées
-   Base de données de production sans données de test
-   Mode debug désactivé
-   Pour passer en production, utilisez :

```bash
APP_ENV=prod composer install --no-dev
```

---

## 📊 Données de test incluses

Les fixtures créent automatiquement :

-   **Catégories de restaurants** : Fast-food, Italien, Japonais, etc.
-   **Catégories de paiement** : Carte Bancaire, Espèce, Tickets Restaurant, Apple Pay, Google Pay, Uber Eats, Deliveroo
-   **Utilisateurs** : 1 administrateur + 4 utilisateurs de test
-   **Restaurants** : 30 restaurants avec images, horaires et catégories
-   **Avis** : 1 à 5 avis par restaurant
