<?php
require 'config.php';

// Récupérer l'ID de la demande et l'action
$id_demunis = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($id_demunis && $action) {
    // Mettre à jour le statut de la demande
    $statut = ($action === 'valider') ? 'valide' : 'rejete';
    $sql = "UPDATE demunis SET statut = :statut WHERE id_demunis = :id_demunis";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':statut' => $statut, ':id_demunis' => $id_demunis]);

    // Rediriger vers la page des demandes
    header("Location: fiche.php");
    exit;
} else {
    echo "Action invalide.";
}
?>