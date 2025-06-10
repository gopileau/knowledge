# Knowledge Learning Platform

## Prérequis

- PHP 7.4 ou supérieur  
- Composer  
- Base de données MySQL ou compatible  
- Serveur web Apache ou Nginx  
- Clé secrète JWT (à définir dans le fichier `.env`)  
- Clés API Stripe pour le paiement (mode sandbox recommandé)  

## Installation

1. Cloner le dépôt GitHub :  
git clone https://github.com/gopileau/knowledge.git

markdown
Copier le code
2. Installer les dépendances PHP avec Composer :  
composer install

markdown
Copier le code
3. Configurer la base de données dans le fichier `config/database.php` ou `.env`  
4. Lancer les migrations pour créer les tables :  
php run_migration.php

markdown
Copier le code
5. Configurer la clé secrète JWT dans `.env` :  
JWT_SECRET=VotreCleSecrete

markdown
Copier le code
6. Configurer les clés API Stripe dans `config/config.php` ou `.env`  

## Authentification

Le système d'authentification utilise :

- **JWT (JSON Web Tokens)** pour une authentification stateless  
- **Sessions PHP** pour rétrocompatibilité  
- **Tokens d'activation par email**  
- **Protection CSRF**  

### Fonctionnalités JWT

- Tokens valables 1 heure  
- Stockage sécurisé en cookies HttpOnly  
- Compatible avec les applications SPA (Single Page Application)  

### Configuration

- Ajouter `JWT_SECRET` dans le fichier `.env`  
- Installer la dépendance JWT avec Composer :  
composer require firebase/php-jwt

markdown
Copier le code
- Les sessions existantes continuent de fonctionner  
- Les nouveaux logins génèrent à la fois session et JWT  

## Lancement

Pour lancer le serveur PHP intégré en développement :  
php -S localhost:8000 -t public

markdown
Copier le code
Puis accéder à l'application via [http://localhost:8000](http://localhost:8000)  

## Tests

Exécuter les tests unitaires avec PHPUnit :  
vendor/bin/phpunit tests/

markdown
Copier le code
Les tests couvrent :  
- Authentification  
- Gestion des utilisateurs  
- Fonctionnalités principales  

## Déploiement

Le projet est déployé sur l’hébergement InfinityFree. Pour déployer :

- Transférez les fichiers du projet via FTP vers votre espace InfinityFree.  
- Configurez la base de données MySQL sur InfinityFree et mettez à jour les paramètres dans `config/database.php` ou `.env`.  
- Assurez-vous que les clés API Stripe et JWT sont configurées dans `.env`.  
- Configurez les permissions nécessaires sur le serveur.  
- Un fichier `deploy.php` est disponible pour un déploiement automatisé sur d’autres serveurs si besoin.  

## Fonctionnalités principales

- Authentification sécurisée avec JWT et sessions PHP  
- Gestion des rôles utilisateurs (admin, client)  
- Panier d'achat et paiement via Stripe  
- Accès aux cours et leçons achetés  
- Validation des leçons et obtention de certifications  
- Interface d'administration pour la gestion des utilisateurs, contenus et commandes  

## Lien vers le dépôt GitHub

[https://github.com/gopileau/knowledge](https://github.com/gopileau/knowledge)  

---

Pour toute question ou contribution, merci de consulter le fichier `/docs`