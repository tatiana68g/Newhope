<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est un Super Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: login.php'); // Rediriger si non connecté ou non autorisé
    exit();
}

$error = ''; // Variable pour afficher les erreurs

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Vérifier si tous les champs sont remplis
    if (empty($nom) || empty($email) || empty($password) || empty($role)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $sql = "SELECT * FROM admins WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                $error = "Un administrateur avec cet email existe déjà.";
            } else {
                // Insérer le nouvel administrateur dans la base de données
                $sql = "INSERT INTO admins (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mot_de_passe, :role)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mot_de_passe', password_hash($password, PASSWORD_DEFAULT));
                $stmt->bindParam(':role', $role);
                $stmt->execute();

                // Redirection vers la page de gestion des administrateurs
                header("Location: manage_admins.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un administrateur</title>
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
        .alert {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color:rgb(245, 111, 111);
            border-color:transparent;
        }
        .btn-primary:hover {
            background-color:rgb(140, 168, 199);
            border-color:rgb(198, 215, 233);
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
            <h4><i class="fas fa-plus"></i> Ajouter un administrateur</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="add_admin.php">
                <div class="mb-3">
                    <label for="nom" class="form-label"><i class="fas fa-user"></i> Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input type="password" id="password" name="mot_de_passe" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Rôle</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="Admin Demandes">Admin Demandes</option>
                        <option value="Admin Projets">Admin Projets</option>
                        <option value="Super Admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Ajouter</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>