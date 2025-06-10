# Modèle Physique de Données - Plateforme Knowledge Learning

Ce document présente le modèle physique de données de l’application.

## Tables et Champs

### users
*Table: Stores user account information.*
- id : int (Clé primaire, auto-incrément)
- name : varchar
- email : varchar (Unique, Index)
- password : varchar
- role : varchar
- is_activated : boolean
- activation_token : varchar
- created_at : timestamp
- updated_at : timestamp

### themes
*Table: Stores themes of courses.*
- id : int (Clé primaire, auto-incrément)
- title : varchar

### cursus
*Table: Stores courses, each belonging to a theme.*
- id : int (Clé primaire, auto-incrément)
- title : varchar
- theme_id : int (Clé étrangère vers themes.id)
- price : decimal

### lessons
*Table: Stores lessons, each belonging to a course.*
- id : int (Clé primaire, auto-incrément)
- title : varchar
- cursus_id : int (Clé étrangère vers cursus.id)
- price : decimal
- video_url : varchar
- content : text

### orders
*Table: Stores order information.*
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- total_price : decimal
- created_at : timestamp

### order_items
*Table: Stores the items included in an order. Can be lessons or courses.*
- id : int (Clé primaire, auto-incrément)
- order_id : int (Clé étrangère vers orders.id)
- lesson_id : int (Clé étrangère vers lessons.id, nullable)
- cursus_id : int (Clé étrangère vers cursus.id, nullable)

### validations
*Table: Stores lesson validations by users.*
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- lesson_id : int (Clé étrangère vers lessons.id)
- validated_at : timestamp

### certifications
*Table: Stores certifications obtained by users for completing courses.*
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- cursus_id : int (Clé étrangère vers cursus.id)
- date_obtained : timestamp

## Relationships

- One `theme` can have many `cursus` (one-to-many relationship).
- One `cursus` can have many `lessons` (one-to-many relationship).
- One `user` can have many `orders` (one-to-many relationship).
- The `order_items` table represents a many-to-many relationship between `orders` and `lessons` or `cursus`.
- One `user` can have many `validations` (one-to-many relationship).
- One `user` can have many `certifications` (one-to-many relationship).
- One `cursus` can have many `certifications` (one-to-many relationship).