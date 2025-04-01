<?php
session_start();
require 'config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

try {
    // Récupérer les projets financés (statut = 'terminé')
    $sql = "SELECT id_projet, titre, description, date_creation FROM projets WHERE statut = 'terminé'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $projets_finances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des projets financés : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des Projets Terminés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 900px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
            font-weight: bold;
        }
        .btn-back {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            background: #6c757d;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .btn-back i {
            margin-right: 8px;
        }
        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        thead {
            background: #343a40;
            color: white;
        }
        tbody tr:hover {
            background: #f1f1f1;
            transition: 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Bouton de retour -->
        <a href="dash.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
        
        <h1>Détails des Projets Terminés</h1>
        <table class="table table-hover table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Date de Financement</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projets_finances)): ?>
                    <?php foreach ($projets_finances as $projet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($projet['id_projet']); ?></td>
                            <td><?php echo htmlspecialchars($projet['titre']); ?></td>
                            <td><?php echo htmlspecialchars($projet['description']); ?></td>
                            <td><?php echo htmlspecialchars($projet['date_creation']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun projet financé trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>