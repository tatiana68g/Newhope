<?php
// Définition des constantes pour la connexion à la base de données
define('DB_HOST', 'localhost');   // Hôte de la base de données (généralement localhost)
define('DB_NAME', 'NewHope');     // Nom de la base de données
define('DB_USER', 'root');        // Utilisateur MySQL
define('DB_PASS', '');            // Mot de passe MySQL (laisser vide si en local)

try {
    // Création de la connexion avec PDO en utilisant les constantes définies dans config.php
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Active les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Mode de récupération par défaut : tableau associatif

    // Affichage d'un message en cas de succès (optionnel, à désactiver en production)
    // echo "Connexion réussie à la base de données !";
} catch (PDOException $e) {
    // Gestion des erreurs si la connexion échoue
    die("Échec de la connexion : " . $e->getMessage());
}
?>