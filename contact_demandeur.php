<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est un Admin Demandes ou Super Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin Demandes' && $_SESSION['role'] !== 'Super Admin')) {
    header('Location: login.php'); // Rediriger si non connecté ou non autorisé
    exit();
}

// Récupérer le numéro de téléphone du demandeur
$sql = "SELECT CONCAT(telephone_individu, ' ', telephone_organisation) AS telephone FROM demunis WHERE statut IN ('valide', 'rejete') LIMIT 1"; // Assurez-vous que 'telephone' est la bonne colonne
$stmt = $pdo->prepare($sql);
$stmt->execute();
$demandeur = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter le demandeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
        }
        .container {
            max-width: 450px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color:rgb(82, 126, 174);
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
<a href="dashboard.php" class="dashboard-btn">Tableau de Bord</a>
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-phone"></i> Contacter le demandeur</h4>
        </div>
        <div class="card-body">
            <?php if ($demandeur): ?>
                <p>Numéro de téléphone du demandeur : <a href="tel:<?php echo htmlspecialchars($demandeur['telephone']); ?>"><?php echo htmlspecialchars($demandeur['telephone']); ?></a></p>
            <?php else: ?>
                <p>Aucun demandeur trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>