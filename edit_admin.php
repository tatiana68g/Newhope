<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est un Super Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: login.php'); // Rediriger si non connecté ou non autorisé
    exit();
}

$error = ''; // Variable pour afficher les erreurs

// Récupérer l'id de l'administrateur à modifier
$id = $_GET['id'];

// Récupérer les informations de l'administrateur
$sql = "SELECT * FROM admins WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    $error = "Administrateur non trouvé.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Vérifier si tous les champs sont remplis
    if (empty($nom) || empty($email) || empty($role)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Mettre à jour les informations de l'administrateur dans la base de données
            $sql = "UPDATE admins SET nom = :nom, email = :email, role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Redirection vers la page de gestion des administrateurs
            header("Location: manage_admins.php");
            exit();
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
    <title>Modifier un administrateur</title>
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
<a href="dash.php" class="dashboard-btn" style="red;">Tableau de Bord</a>
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-edit"></i> Modifier un administrateur</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit_admin.php?id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label"><i class="fas fa-user"></i> Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlspecialchars($admin['nom']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Rôle</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="Admin Demandes" <?php if ($admin['role'] == 'Admin Demandes') echo 'selected'; ?>>Admin Demandes</option>
                        <option value="Admin Projets" <?php if ($admin['role'] == 'Admin Projets') echo 'selected'; ?>>Admin Projets</option>
                        <option value="Super Admin" <?php if ($admin['role'] == 'Super Admin') echo 'selected'; ?>>Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Enregistrer</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>