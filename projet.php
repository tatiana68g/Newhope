<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $id_demunis = $_POST['id_demunis'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $type_aide = $_POST['type_aide'];
    $delais = $_POST['delais'];

    // Gestion des images
    $images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $image) {
            $imageTmpName = $_FILES['images']['tmp_name'][$key];
            $imageName = basename($image);
            $imagePath = 'uploads/' . $imageName;
            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $images[] = $imagePath;
            }
        }
    }
    $images = implode(',', $images);

    // Insérer les données dans la base de données
    $sql = "INSERT INTO projets (id_demunis, titre, description, type_aide, images, delais, date_creation) VALUES (:id_demunis, :titre, :description, :type_aide, :images, :delais, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_demunis', $id_demunis);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':type_aide', $type_aide);
    $stmt->bindParam(':images', $images);
    $stmt->bindParam(':delais', $delais);

    if ($stmt->execute()) {
        echo "Projet créé avec succès.";
    } else {
        echo "Erreur lors de la création du projet.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Projet - NewHope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(145deg, #f8cdda, #1e2a47);
            color: #333;
        }
        .form-container {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
        .desc-cell {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .table img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
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
        .status-icon {
            font-size: 1.2rem;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(145deg, #f8cdda, #1e2a47);
            color: #333;
        }
        .container {
            max-width: 1500px;
            margin-top: 50px;
        }
        .form-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
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
        .back-btn {
            background-color: rgb(228, 147, 147);
            color: white;
            border-radius: 12px;
            padding: 10px;
            font-size: 1.1rem;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .back-btn:hover {
            background-color: rgb(255, 19, 19);
        }
        .card {
            margin-bottom: 20px;
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    
<div class="container">
<div class="form-container">
    <h1 class="text-center"><i class="fas fa-project-diagram"></i> Créer un Projet</h1>
    <p class="text-center"><i class="fas fa-pencil-alt"></i> Remplissez ce formulaire pour soumettre un projet.</p>

    <form action="projet.php" method="POST" enctype="multipart/form-data">
        <!-- ID Demunis -->
        <div class="mb-3">
            <label for="id_demunis" class="form-label"><i class="fas fa-user"></i> ID du Demunis :</label>
            <input type="number" id="id_demunis" name="id_demunis" class="form-control" required>
        </div>

        <!-- Titre -->
        <div class="mb-3">
            <label for="titre" class="form-label"><i class="fas fa-heading"></i> Titre du projet :</label>
            <input type="text" id="titre" name="titre" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description :</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <!-- Type d'Aide -->
        <div class="mb-3">
            <label for="type_aide" class="form-label"><i class="fas fa-hand-holding-heart"></i> Type d'aide :</label>
            <select name="type_aide" id="type_aide" class="form-select" required>
                <option value="Financière">Financière</option>
                <option value="Alimentaire">Aide Alimentaire</option>
                <option value="Sanitaire">Aide Sanitaire</option>
                <option value="Éducation">Éducation</option>
            </select>
        </div>

        <!-- Téléchargement des images -->
        <div class="mb-3">
            <label for="images" class="form-label"><i class="fas fa-images"></i> Images du projet :</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple>
        </div>

        <!-- Délai du projet -->
        <div class="mb-3">
            <label for="delais" class="form-label"><i class="fas fa-clock"></i> Délai (en jours) :</label>
            <input type="number" id="delais" name="delais" class="form-control" min="1" required>
        </div>

        <!-- Bouton Soumettre -->
        <button type="submit" class="btn btn-custom w-100"><i class="fas fa-check-circle"></i> Créer le projet</button>
    </form>

    <!-- Bouton Retour -->
    <a href="arrow.php" class="back-btn"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
</div>


<div class="container">
    <h2 class="text-center mb-4" style="color:#Efff4f; font-size:1.5em;"><i class="fas fa-table"></i> Liste des Projets</h2>

    <div class="table-container">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ID Demunis</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Type d'Aide</th>
                    <th>Images</th>
                    <th>Date de Création</th>
                    <th>Délai (Jours)</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM projets ORDER BY date_creation DESC");
                while ($projet = $stmt->fetch()) {
                ?>
                    <tr>
                        <td><?= htmlspecialchars($projet['id_projet']); ?></td>
                        <td><?= htmlspecialchars($projet['id_demunis']); ?></td>
                        <td><?= htmlspecialchars($projet['titre']); ?></td>
                        <td class="desc-cell" title="<?= htmlspecialchars($projet['description']); ?>">
                            <?= substr(htmlspecialchars($projet['description']), 0, 100) . '...'; ?>
                        </td>
                        <td><?= htmlspecialchars($projet['type_aide']); ?></td>
                        <td>
                            <?php
                            if (!empty($projet['images'])) {
                                $images = explode(',', $projet['images']);
                                foreach ($images as $img) {
                                    if (!empty($img)) {
                                        echo "<img src='" . htmlspecialchars($img) . "' alt='Projet'>";
                                    }
                                }
                            } else {
                                echo "Pas d'image";
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($projet['date_creation']); ?></td>
                        <td><?= htmlspecialchars($projet['delais']); ?></td>
                        <td>
                            <form method="POST" action="changer_statut.php">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($projet['id_projet']); ?>">
                                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="en cours" <?= ($projet['statut'] == 'en cours') ? 'selected' : ''; ?>>
                                        🟡 En cours
                                    </option>
                                    <option value="terminé" <?= ($projet['statut'] == 'terminé') ? 'selected' : ''; ?>>
                                        ✅ Terminé
                                    </option>
                                    <option value="rejeté" <?= ($projet['statut'] == 'rejeté') ? 'selected' : ''; ?>>
                                        ❌ Rejeté
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="delete_projet.php">
                                <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                            <form method="GET" action="Update.php">
                                <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']); ?>">
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<a href="dash.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
        

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>