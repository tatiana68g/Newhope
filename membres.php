<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Membre - NewHope</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
        }
        .form-container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center">Devenir Membre</h2>
                    <!-- Formulaire d'inscription -->
                    <form action="A_propos.php" method="POST">
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone :</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Entrez votre numéro de téléphone" required>
                        </div>
                        <div class="form-group">
                            <label for="adresse">Adresse :</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez votre adresse" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);

    try {
        // Connexion à la base de données avec PDO
        $dsn = 'mysql:host=localhost;dbname=newhope;charset=utf8';
        $username = 'root';
        $password = '';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $pdo = new PDO($dsn, $username, $password, $options);

        // Préparer et exécuter la requête d'insertion
        $sql = "INSERT INTO membres (nom, email, telephone, adresse) VALUES (:nom, :email, :telephone, :adresse)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
        ]);

        // Redirection vers la page d'accueil avec un paramètre de succès
        header("Location: A_propos.php?inscription=success");
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs
        echo "<script>alert('Erreur lors de l\'inscription : " . $e->getMessage() . "'); window.location.href='membres.php';</script>";
    }
}
?>