



<a href="dashboard.php" class="dashboard-btn">Tableau de Bord</a>
<form method="POST" action="projet.php">
    <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']); ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" id="titre" class="form-control" value="<?= htmlspecialchars($projet['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($projet['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="type_aide" class="form-label">Type d'aide</label>
        <input type="text" name="type_aide" id="type_aide" class="form-control" value="<?= htmlspecialchars($projet['type_aide']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
    <a href="projet.php" class="btn btn-secondary">Annuler</a>
</form>
<?php
require 'config.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si les données nécessaires sont envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id_projet'], $_POST['titre'], $_POST['description'], $_POST['type_aide'])) {
        header("Location: projet.php?error=Champs manquants !");
        exit();
    }

    $id_projet = intval($_POST['id_projet']);
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $type_aide = trim($_POST['type_aide']);

    if ($id_projet <= 0 || empty($titre) || empty($description) || empty($type_aide)) {
        header("Location: projet.php?error=Valeurs invalides !");
        exit();
    }

    // Préparer et exécuter la requête pour modifier le projet
    $stmt = $pdo->prepare("UPDATE projets SET titre = ?, description = ?, type_aide = ? WHERE id_projet = ?");
    if ($stmt->execute([$titre, $description, $type_aide, $id_projet])) {
        // Rediriger avec un message de succès
        header("Location: projet.php?message=Projet modifié avec succès");
        exit();
    } else {
        // Afficher une erreur en cas d'échec
        header("Location: projet.php?error=Erreur lors de la modification !");
        exit();
    }
} else {
    // Si la méthode n'est pas POST, afficher une erreur
    header("Location: projet.php?error=Requête invalide !");
    exit();
}
?>
