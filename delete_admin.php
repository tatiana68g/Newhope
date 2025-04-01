<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est un Super Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: login.php'); // Rediriger si non connecté ou non autorisé
    exit();
}

// Récupérer l'id de l'administrateur à supprimer
$id = $_GET['id'];

try {
    // Supprimer l'administrateur de la base de données
    $sql = "DELETE FROM admins WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirection vers la page de gestion des administrateurs
    header("Location: manage_admins.php");
    exit();
} catch (PDOException $e) {
    $error = "Erreur de base de données : " . $e->getMessage();
}
?>