<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Activer les erreurs PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$error = ''; // Variable pour afficher les erreurs

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    // Vérifier si l'email et le mot de passe sont fournis
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Vérifier si l'email existe dans la base de données
            $sql = "SELECT * FROM admins WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Vérifier si le mot de passe est correct
                if (password_verify($password, $admin['mot_de_passe'])) {
                    // Authentification réussie
                    $_SESSION['id'] = $admin['id']; // Enregistrer l'id de l'administrateur dans la session
                    $_SESSION['email'] = $admin['email']; // Enregistrer l'email de l'administrateur dans la session
                    $_SESSION['nom'] = $admin['nom']; // Enregistrer le nom de l'administrateur dans la session
                    $_SESSION['role'] = $admin['role']; // Enregistrer le rôle de l'administrateur dans la session

                    // Redirection vers le tableau de bord
                    header("Location: dash.php");
                    exit();
                } else {
                    $error = "Mot de passe incorrect.";
                }
            } else {
                $error = "Aucun administrateur trouvé avec cet email.";
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
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
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
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-sign-in-alt"></i> Connectez-vous</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input type="password" id="password" name="mot_de_passe" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>