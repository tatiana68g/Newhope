<?php
session_start();
require 'config.php';

// Vérifier si un administrateur est connecté
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
$nom = $_SESSION['nom'];

// Enregistrer les paramètres
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'];
    $admin_email = $_POST['admin_email'];

    $sql = "UPDATE settings SET site_name = ?, admin_email = ? WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$site_name, $admin_email]);

    $message = "Paramètres mis à jour avec succès.";
}

// Récupérer les paramètres actuels
$sql = "SELECT * FROM settings WHERE id = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - NewHope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #25D366;
            border-color: #25D366;
        }

        .btn-primary:hover {
            background-color: #1DA851;
            border-color: #1DA851;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: transparent;
            padding: 20px;
        }

        .card-icon {
            font-size: 50px;
            margin-bottom: 15px;
            color: #25D366;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Paramètres</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-success text-center"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="site_name" class="form-label">Nom du site</label>
                <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo $settings['site_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">Email de l'administrateur</label>
                <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo $settings['admin_email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
        </form>
    </div>
</body>
</html>