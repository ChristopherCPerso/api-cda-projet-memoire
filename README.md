# 🚀 API AtecNAdvisor

Bienvenue dans l'API AtecNAdvisor, construite avec Symfony. Ce document vous guidera à travers l'installation, le développement, le déploiement et la mise en production du projet.

---

## 🛠️ Prérequis

Pour lancer ce projet en local, assurez-vous d'avoir les éléments suivants installés :

-   **PHP >= 8.1**
-   **Composer**
-   **Docker & Docker Compose**
-   **Symfony CLI** (optionnel mais recommandé)

---

## 🚀 Installation pas à pas

Suivez ces étapes pour configurer et lancer le projet sur votre machine locale.

### 1. Cloner le dépôt

```bash
git clone https://github.com/ChristopherCPerso/api-cda-projet-memoire
cd api-atecnadvisor # Ou le nom de votre dossier de projet
```

### 2. Installation des dépendances

Le projet utilise Composer pour la gestion des dépendances PHP et Docker pour l'environnement de développement.

```bash
composer install
```

### 3. Fichier d'environnement (.env)

Pour des raisons de sécurité, le fichier `.env` contenant les configurations sensibles (comme les identifiants de base de données et les clés JWT) n'est pas inclus dans le dépôt.

**Veuillez récupérer le fichier `.env` auprès du lead développeur.**

Une fois en votre possession, placez-le à la racine du projet.

### 4. Lancement de l'environnement Docker

Lancez les services Docker définis dans `docker-compose.yml` :

```bash
docker-compose up -d
```

Cela démarrera les conteneurs nécessaires (base de données, etc.).

### 5. Configuration de la base de données

Après le démarrage des conteneurs, configurez votre base de données :

```bash
# Crée la base de données (si elle n'existe pas)
php bin/console doctrine:database:create

# Applique les migrations pour créer les tables
php bin/console doctrine:migrations:migrate
```

### 6. Chargement des données de test (environnement de développement)

Si vous êtes en environnement de développement, vous pouvez charger des données de test via les fixtures :

```bash
# Les fixtures s'exécutent uniquement en environnement dev
php bin/console doctrine:fixtures:load --env=dev
```

### 7. Création des clés JWT

Pour l'authentification via JWT, vous devez générer une clé privée et une clé publique.

#### Clé privée :

```bash
openssl genpkey \
    -algorithm RSA \
    -out config/jwt/private.pem \
    -aes-256-cbc \
    -pkeyopt rsa_keygen_bits:4096
```

**Attention :** Vous serez invité à entrer une phrase secrète (passphrase). Choisissez-en une robuste et **notez-la**. Vous devrez l'ajouter dans votre fichier `.env.local` (variable `JWT_PASSPHRASE`).

#### Clé publique :

```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

### 8. Lancement du serveur Symfony

Lancez le serveur web local de Symfony :

```bash
symfony server:start
```

Votre API devrait maintenant être accessible localement.

---

## 💻 Développement Frontend (TypeScript)

Ce projet backend est conçu pour interagir avec une application frontend qui utilise **TypeScript**. Voici quelques points à garder à l'esprit :

-   **Types statiques :** TypeScript apporte une robustesse au code en détectant les erreurs avant l'exécution. Assurez-vous que vos modèles de données frontend correspondent aux schémas de l'API.
-   **Intégration de l'API :** Utilisez des outils comme `axios` ou `fetch` pour interagir avec l'API.
-   **Génération de types :** Envisagez d'utiliser des outils pour générer automatiquement les types TypeScript à partir de la documentation OpenAPI (souvent disponible via API Platform). Cela garantit la cohérence entre le frontend et le backend.

---

## 🤝 Workflow de développement et déploiement sur le dépôt

Pour maintenir un code propre et une collaboration efficace, suivez ces bonnes pratiques :

### 1. Une tâche, une branche

Chaque nouvelle fonctionnalité, amélioration ou correction de bug doit être développée sur une branche Git dédiée. Nommez vos branches de manière descriptive, par exemple :

-   `feat/nom-de-la-fonctionnalite`
-   `enhance/amelioration-existante`
-   `fix/correction-de-bug`
-   `chore/tache-technique`

### 2. Vérification avant le push

Avant de pousser vos modifications sur le dépôt distant, assurez-vous de :

-   Vérifier qu'il ne reste pas de dump, ni de code mort.
-   Exécuter les tests unitaires et fonctionnels (`php bin/phpunit`).

### 3. Création d'une Pull Request (PR) / Merge Request (MR)

Une fois votre branche prête, créez une Pull Request (sur GitHub/GitLab/Bitbucket) avec les informations suivantes :

-   **Type d'action :** `feat`, `enhance`, `fix`, `chore`, etc.
-   **Numéro de ticket :** Référence à la tâche dans votre outil de gestion de projet (ex: `#123`).
-   **Description :** Expliquez clairement ce que fait votre modification, pourquoi elle a été faite, et comment la tester si nécessaire.

---

## 🚀 Mise en production

Le processus de mise en production est déclenché par le merge sur la branche `main`.

### 1. Merge sur la branche `main`

Une fois qu'une Pull Request est approuvée et que les tests passent, elle peut être fusionnée dans la branche `main`. **Seules les branches stables et validées doivent être fusionnées sur `main`.**

### 2. Déploiement automatique

Le merge sur la branche `main` devrait déclencher automatiquement le pipeline de CI/CD (intégration continue / déploiement continu) qui :

-   Construira l'application.
-   Déploiera l'application sur l'environnement de production.

### 3. Environnement de production

L'environnement de production est optimisé pour la performance et la sécurité :

-   Les fixtures ne sont PAS installées.
-   Base de données de production sans données de test.
-   Mode debug désactivé.

Pour installer les dépendances en mode production, utilisez :

```bash
APP_ENV=prod composer install --no-dev
```
