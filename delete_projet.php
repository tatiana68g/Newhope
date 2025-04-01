<?php
require 'config.php';

//-----------------------------Pour Supprimer-------------------------------
// Vérifier si des données sont reçues
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST); // Voir ce qui est reçu
    if (!isset($_POST['id_projet'])) {
        die("Erreur : ID du projet non reçu !");
    }

    $id_projet = intval($_POST['id_projet']);

    // Vérifier si l'ID est valide
    if ($id_projet <= 0) {
        die("Erreur : ID de projet invalide !");
    }

    // Vérifier si le projet existe avant suppression
    $check_stmt = $pdo->prepare("SELECT id_projet FROM projets WHERE id_projet = ?");
    $check_stmt->execute([$id_projet]);
    if ($check_stmt->rowCount() == 0) {
        die("Erreur : Projet introuvable !");
    }

    // Supprimer le projet
    $stmt = $pdo->prepare("DELETE FROM projets WHERE id_projet = ?");
    if ($stmt->execute([$id_projet])) {
        header("Location: projet.php?message=Projet supprimé avec succès");
        exit();
    } else {
        die("Erreur lors de la suppression !");
    }
} else {
    die("Requête invalide !");
}
?>
