<?php
/**
 * Serveur de développement intégré
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Ce fichier permet d'émuler la fonctionnalité 'mod_rewrite' d'Apache
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
