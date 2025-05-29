# Knowledge Learning Platform

## Authentification

Le système d'authentification utilise :
- JWT (JSON Web Tokens) pour l'authentification stateless
- Sessions PHP pour la rétrocompatibilité  
- Tokens d'activation par email
- Protection CSRF

### Nouveautés JWT

1. **Fonctionnalités :**
   - Tokens valables 1 heure
   - Stockage sécurisé en cookies HttpOnly
   - Compatible avec les applications SPA

2. **Configuration :**
   - Ajouter `JWT_SECRET` dans le .env
   - Installer la dépendance : `composer require firebase/php-jwt`

3. **Migration :**
   - Les sessions existantes continuent de fonctionner
   - Nouveaux logins génèrent à la fois session et JWT

## Installation

1. Cloner le dépôt
2. `composer install`
3. Configurer la base de données
4. Lancer les migrations

## Tests

Exécuter les tests avec :
```bash
phpunit tests/
```

Les tests couvrent :
- Authentification
- Gestion des utilisateurs
- Fonctionnalités principales
