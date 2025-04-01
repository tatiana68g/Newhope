<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["statut"])) {
    $id_projet = $_POST["id"];
    $nouveau_statut = $_POST["statut"];

    $stmt = $pdo->prepare("UPDATE projets SET statut = ? WHERE id_projet = ?");
    $stmt->execute([$nouveau_statut, $id_projet]);

    header("Location: projet.php"); // Redirection vers la liste des projets
    exit();
}
?>
