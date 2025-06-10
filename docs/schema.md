# Modèle Physique de Données - Plateforme Knowledge Learning

Ce document présente le modèle physique de données de l’application.

## Tables et Champs
 
 ### users
-*Table: Stores user account information.*
+*Table : Stocke les informations des comptes utilisateurs.*
 - id : int (Clé primaire, auto-incrément)
 - name : varchar
 - email : varchar (Unique, Index)
 - password : varchar
@@ -16,61 +16,61 @@
 - created_at : timestamp
 - updated_at : timestamp
 
 ### themes
-*Table: Stores themes of courses.*
+*Table : Stocke les thèmes des cours.*
 - id : int (Clé primaire, auto-incrément)
 - title : varchar
 
 ### cursus
-*Table: Stores courses, each belonging to a theme.*
+*Table : Stocke les cursus, chacun appartenant à un thème.*
 - id : int (Clé primaire, auto-incrément)
 - title : varchar
 - theme_id : int (Clé étrangère vers themes.id)
 - price : decimal
 
 ### lessons
-*Table: Stores lessons, each belonging to a course.*
+*Table : Stocke les leçons, chacune appartenant à un cursus.*
 - id : int (Clé primaire, auto-incrément)
 - title : varchar
 - cursus_id : int (Clé étrangère vers cursus.id)
 - price : decimal
 - video_url : varchar
 - content : text
 
 ### orders
-*Table: Stores order information.*
+*Table : Stocke les informations des commandes.*
 - id : int (Clé primaire, auto-incrément)
 - user_id : int (Clé étrangère vers users.id)
 - total_price : decimal
 - created_at : timestamp
 
 ### order_items
-*Table: Stores the items included in an order. Can be lessons or courses.*
+*Table : Stocke les éléments inclus dans une commande. Peut être des leçons ou des cursus.*
 - id : int (Clé primaire, auto-incrément)
 - order_id : int (Clé étrangère vers orders.id)
 - lesson_id : int (Clé étrangère vers lessons.id, nullable)
 - cursus_id : int (Clé étrangère vers cursus.id, nullable)
 
 ### validations
-*Table: Stores lesson validations by users.*
+*Table : Stocke les validations des leçons par les utilisateurs.*
 - id : int (Clé primaire, auto-incrément)
 - user_id : int (Clé étrangère vers users.id)
 - lesson_id : int (Clé étrangère vers lessons.id)
 - validated_at : timestamp
 
 ### certifications
-*Table: Stores certifications obtained by users for completing courses.*
+*Table : Stocke les certifications obtenues par les utilisateurs pour avoir complété des cursus.*
 - id : int (Clé primaire, auto-incrément)
 - user_id : int (Clé étrangère vers users.id)
 - cursus_id : int (Clé étrangère vers cursus.id)
 - date_obtained : timestamp
 
-## Relationships
+## Relations
\ No newline at end of file
 
-- One `theme` can have many `cursus` (one-to-many relationship).
-- One `cursus` can have many `lessons` (one-to-many relationship).
-- One `user` can have many `orders` (one-to-many relationship).
-- The `order_items` table represents a many-to-many relationship between `orders` and `lessons` or `cursus`.
-- One `user` can have many `validations` (one-to-many relationship).
-- One `user` can have many `certifications` (one-to-many relationship).
-- One `cursus` can have many `certifications` (one-to-many relationship).
+- Un `theme` peut avoir plusieurs `cursus` (relation un-à-plusieurs).
+- Un `cursus` peut avoir plusieurs `lessons` (relation un-à-plusieurs).
+- Un `user` peut avoir plusieurs `orders` (relation un-à-plusieurs).
+- La table `order_items` représente une relation plusieurs-à-plusieurs entre `orders` et `lessons` ou `cursus`.
+- Un `user` peut avoir plusieurs `validations` (relation un-à-plusieurs).
+- Un `user` peut avoir plusieurs `certifications` (relation un-à-plusieurs).
+- Un `cursus` peut avoir plusieurs `certifications` (relation un-à-plusieurs).