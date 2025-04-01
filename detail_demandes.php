<?php
session_start();
require 'config.php';

// Vérifier si un administrateur est connecté
if (!isset($_SESSION['role'])) {
    header('Location: login.php'); // Rediriger si non connecté
    exit();
}

// Récupérer toutes les demandes d'aide
try {
    $sql = "SELECT * FROM demunis ORDER BY date_soumission DESC"; // Récupérer les demandes par ordre de soumission
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer toutes les demandes sous forme de tableau associatif
} catch (PDOException $e) {
    die("Erreur lors de la récupération des demandes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des Demandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h2 {
            color: rgb(2, 7, 12);
            font-weight: bold;
            font-size: 1.5em;
        }
        .table th {
            background-color: rgb(219, 100, 64);
            color: rgb(241, 234, 234);
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            border: 0.2px solid #ddd;
            padding: 5px;
        }
        .no-data {
            font-size: 18px;
            color: #6c757d;
        }
        .badge {
            font-size: 14px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Liste des Demandes d'Aide</h2>
        <div class="table-responsive"> <!-- Ajout de la classe table-responsive -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>id_demunis</th>
                        <th>Type de Demandeur</th>
                        <th>Type d'Aide</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Âge</th>
                        <th>Raison/Objectif</th>
                        <th>Preuve/Document</th>
                        <th>Date de Soumission</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($demandes)): ?>
                        <?php foreach ($demandes as $index => $demande): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($demande['type_demandeur']); ?></td>
                                <td><?php echo htmlspecialchars($demande['type_aide']); ?></td>
                                <td><?php echo htmlspecialchars(isset($demande['nom_individu']) ? $demande['nom_individu'] : $demande['nom_organisation']); ?></td>
                                <td><?php echo htmlspecialchars(isset($demande['email_individu']) ? $demande['email_individu'] : $demande['email_organisation']); ?></td>
                                <td><?php echo htmlspecialchars(isset($demande['telephone_individu']) ? $demande['telephone_individu'] : $demande['telephone_organisation']); ?></td>
                                <td><?php echo htmlspecialchars(isset($demande['adresse_individu']) ? $demande['adresse_individu'] : $demande['adresse_organisation']); ?></td>
                                <td><?php echo htmlspecialchars(isset($demande['age']) ? $demande['age'] : '-'); ?></td>
                                <td>
                                    <?php
                                    if ($demande['type_demandeur'] === 'Individu') {
                                        echo htmlspecialchars($demande['raison_individu']);
                                    } else {
                                        echo htmlspecialchars($demande['objectif_organisation']);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($demande['type_demandeur'] === 'Individu') {
                                        if (!empty($demande['preuve_identite']) && file_exists($demande['preuve_identite'])) {
                                            echo '<img src="' . htmlspecialchars($demande['preuve_identite']) . '" alt="Preuve d\'identité">';
                                        } else {
                                            echo 'Aucune preuve disponible';
                                        }
                                    } else {
                                        if (!empty($demande['documents_enregistrement']) && file_exists($demande['documents_enregistrement'])) {
                                            echo '<img src="' . htmlspecialchars($demande['documents_enregistrement']) . '" alt="Document d\'enregistrement">';
                                        } else {
                                            echo 'Aucun document disponible';
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($demande['date_soumission']); ?></td>
                                <td>
                                    <?php
                                    if ($demande['statut'] === 'en_attente') {
                                        echo '<span class="badge bg-warning text-dark">En attente</span>';
                                    } elseif ($demande['statut'] === 'approuve') {
                                        echo '<span class="badge bg-success">Approuvé</span>';
                                    } elseif ($demande['statut'] === 'rejete') {
                                        echo '<span class="badge bg-danger">Rejeté</span>';
                                    } else {
                                        echo htmlspecialchars($demande['statut']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center no-data">Aucune demande trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>