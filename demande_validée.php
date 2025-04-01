<?php
require 'config.php';

// Récupérer les demandes validées
$sql_demandes = "SELECT * FROM demunis WHERE statut = 'valide' ORDER BY date_soumission DESC";
$stmt_demandes = $pdo->prepare($sql_demandes);
$stmt_demandes->execute();
$demandes = $stmt_demandes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewHope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(145deg,rgb(235, 226, 229),rgb(228, 230, 235));
            color: #333;
        }
        .table-container {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }
        .details-row {
            background-color: #f9f9f9;
        }
        .btn-custom {
            background-color: rgb(10, 223, 64);
            color: white;
            border-radius: 12px;
            padding: 10px;
            font-size: 1.1rem;
        }
        .btn-custom:hover {
            background-color: rgb(224, 144, 53);
        }
        .btn-details, .btn-create {
            border-radius: 12px;
            padding: 5px 10px;
            font-size: 0.9rem;
            color: white;
        }
        .btn-details {
            background-color: #007bff;
        }
        .btn-details:hover {
            background-color: #0056b3;
        }
        .btn-create {
            background-color: #28a745;
        }
        .btn-create:hover {
            background-color: #218838;
        }
        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
    <a href="dashboard.php" class="dashboard-btn">Tableau de Bord</a>
        <h2 class="text-center mb-4">Demande_Validée</h2>

        <!-- Liste des demandes validées -->
        <h3>Demandes validées</h3>
        <?php if (count($demandes) > 0): ?>
            <div class="table-container">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Demunis</th>
                            <th>Type de Demandeur</th>
                            <th>Nom</th>
                            <th>Type d'Aide</th>
                            <th>Date de Soumission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($demandes as $demande): ?>
                            <!-- Ligne principale -->
                            <tr data-bs-toggle="collapse" data-bs-target="#details-<?= $demande['id_demunis']; ?>" aria-expanded="false">
                                <td><?= htmlspecialchars($demande['id_demunis']); ?></td>
                                <td><?= htmlspecialchars($demande['type_demandeur']); ?></td>
                                <td><?= htmlspecialchars($demande['type_demandeur'] == 'Individu' ? $demande['nom_individu'] : $demande['nom_organisation']); ?></td>
                                <td><?= htmlspecialchars($demande['type_aide']); ?></td>
                                <td><?= htmlspecialchars($demande['date_soumission']); ?></td>
                                <td>
                                    <a href="projet.php?id=<?= $demande['id_demunis']; ?>" class="btn btn-custom">Voir+</a>
                                </td>
                            </tr>
                            <!-- Ligne dépliante pour les détails -->
                            <tr class="collapse details-row" id="details-<?= $demande['id_demunis']; ?>">
                                <td colspan="6">
                                    <div class="p-3">
                                        <h5>Détails de la Demande</h5>
                                        <?php if ($demande['type_demandeur'] == 'Individu'): ?>
                                            <p><strong>Nom :</strong> <?= htmlspecialchars($demande['nom_individu']); ?></p>
                                            <p><strong>Âge :</strong> <?= htmlspecialchars($demande['age']); ?></p>
                                            <p><strong>Adresse :</strong> <?= htmlspecialchars($demande['adresse_individu']); ?></p>
                                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone_individu']); ?></p>
                                            <p><strong>Email :</strong> <?= htmlspecialchars($demande['email_individu']); ?></p>
                                        <?php else: ?>
                                            <p><strong>Nom de l'Organisation :</strong> <?= htmlspecialchars($demande['nom_organisation']); ?></p>
                                            <p><strong>Type d'Organisation :</strong> <?= htmlspecialchars($demande['type_organisation']); ?></p>
                                            <p><strong>Adresse :</strong> <?= htmlspecialchars($demande['adresse_organisation']); ?></p>
                                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone_organisation']); ?></p>
                                            <p><strong>Email :</strong> <?= htmlspecialchars($demande['email_organisation']); ?></p>
                                        <?php endif; ?>
                                        <p><strong>Type d'Aide Demandée :</strong> <?= htmlspecialchars($demande['type_aide']); ?></p>
                                        <p><strong>Date de Soumission :</strong> <?= htmlspecialchars($demande['date_soumission']); ?></p>

                                        <!-- Bouton Créer un Projet -->
                                        <div class="mt-3">
                                            <a href="projet.php?id=<?= $demande['id_demunis']; ?>" class="btn btn-create">
                                                <i class="fas fa-plus"></i> Créer un projet
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Aucune demande validée.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
