# Modèle Physique de Données - Plateforme Knowledge Learning

Ce document présente le modèle physique de données de l’application.

## Tables et Champs

### users
- id : int (Clé primaire, auto-incrément)
- name : varchar
- email : varchar (Unique)
- password : varchar
- role : varchar
- is_activated : boolean
- activation_token : varchar
- created_at : timestamp
- updated_at : timestamp

### themes
- id : int (Clé primaire, auto-incrément)
- title : varchar

### cursus
- id : int (Clé primaire, auto-incrément)
- title : varchar
- theme_id : int (Clé étrangère vers themes.id)
- price : decimal

### lessons
- id : int (Clé primaire, auto-incrément)
- title : varchar
- cursus_id : int (Clé étrangère vers cursus.id)
- price : decimal
- video_url : varchar
- content : text

### orders
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- total_price : decimal
- created_at : timestamp

### order_items
- id : int (Clé primaire, auto-incrément)
- order_id : int (Clé étrangère vers orders.id)
- lesson_id : int (Clé étrangère vers lessons.id, nullable)
- cursus_id : int (Clé étrangère vers cursus.id, nullable)

### validations
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- lesson_id : int (Clé étrangère vers lessons.id)
- validated_at : timestamp

### certifications
- id : int (Clé primaire, auto-incrément)
- user_id : int (Clé étrangère vers users.id)
- cursus_id : int (Clé étrangère vers cursus.id)
- date_obtained : timestamp

