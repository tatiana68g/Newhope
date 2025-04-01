<?php
session_start();
require 'config.php'; // Connexion à la base de données

$error = ''; // Variable pour afficher les erreurs

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'email et le mot de passe sont fournis
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Vérifier si le nombre d'administrateurs est inférieur à 2
        $sql_count = "SELECT COUNT(*) FROM administrateurs";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->execute();
        $admin_count = $stmt_count->fetchColumn();

        if ($admin_count >= 2) {
            $error = "Le nombre d'administrateurs est limité à 2.";
        } else {
            // Vérifier si l'email existe déjà
            $sql_check = "SELECT * FROM administrateurs WHERE email = :email";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':email', $email);
            $stmt_check->execute();
            $admin_exists = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($admin_exists) {
                $error = "Cet email est déjà utilisé.";
            } else {
                // Hash du mot de passe
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insérer l'administrateur dans la base de données
                $sql_insert = "INSERT INTO administrateurs (email, password) VALUES (:email, :password)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->bindParam(':email', $email);
                $stmt_insert->bindParam(':password', $hashed_password);
                $stmt_insert->execute();

                // Rediriger vers la page de connexion
                header("Location: login.php"); // Rediriger vers la page de connexion après inscription
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
        }
        .container {
            max-width: 450px;
            margin-top: 200px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color:rgb(243, 69, 69);
            border-color:transparent;
        }
        .btn-primary:hover {
            background-color:rgb(89, 137, 188);
            border-color:rgb(229, 159, 183);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color:rgb(240, 139, 139);
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
    <div class="card">
        <div class="card-header">
            <h4>Inscription Administrateur</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
