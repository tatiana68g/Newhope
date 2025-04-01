<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est un Super Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: login.php'); // Rediriger si non connecté ou non autorisé
    exit();
}

// Récupérer la liste des administrateurs
$sql = "SELECT * FROM admins";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les administrateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
        }
        .container {
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
<a href="dash.php" class="dashboard-btn">Tableau de Bord</a>
<div class="card">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-users-cog"></i> Gérer les administrateurs</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td><?php echo htmlspecialchars($admin['nom']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars($admin['role']); ?></td>
                            <td>
                                <a href="edit_admin.php?id=<?php echo $admin['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                                <a href="delete_admin.php?id=<?php echo $admin['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');"><i class="fas fa-trash"></i> Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="add_admin.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un administrateur</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>