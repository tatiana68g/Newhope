<?php  
session_start();
require 'config.php';  

// Vérifier si un administrateur est connecté et récupérer son rôle
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin Projets') {
    header('Location: login.php'); // Rediriger si non connecté ou si le rôle n'est pas Admin Projets
    exit();
}

$role = $_SESSION['role'];  // Récupérer le rôle de l'utilisateur
$nom = $_SESSION['nom'];  // Récupérer le nom de l'utilisateur

// Récupérer les dons pour l'administrateur des projets
$donations = [];
$sql_donations = "SELECT * FROM dons"; 
$stmt_donations = $pdo->prepare($sql_donations);
$stmt_donations->execute();
$donations = $stmt_donations->fetchAll(PDO::FETCH_ASSOC);

?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Consulter les dons - NewHope</title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />  
    <style>  
        body {  
            font-family: 'Public Sans', sans-serif;  
            background-color: #f9f9f9;  
            color: #333;  
        }  

        .topbar {  
            background-color: #ff6f61;  
            color: white;  
            padding: 15px 20px;  
            display: flex;  
            align-items: center;  
            justify-content: space-between;  
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);  
        }  

        .menu-btn {  
            font-size: 20px;  
            cursor: pointer;  
        }  

        .dashboard-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .dashboard-btn:hover {
            background-color: #218838;
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

        table {  
            width: 100%;  
            border-collapse: collapse;  
        }  

        th, td {  
            padding: 12px;  
            text-align: left;  
            border-bottom: 1px solid #ddd;  
        }  

        th {  
            background-color: #f2f2f2;  
        }  

    </style>  
</head>  
<body>  
    <!-- Navbar -->  
    <div class="topbar">  
        <div class="menu-btn" onclick="history.back()">  
            <i class="fas fa-arrow-left"></i>  
        </div>  
        <a href="dashboard.php" class="dashboard-btn">Tableau de Bord</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>

    <!-- Main Content -->  
    <div class="container mt-4">  
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-donate"></i> Dons effectués</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($donations) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Donateur</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($donation['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($donation['montant']); ?></td>
                                            <td><?php echo htmlspecialchars($donation['date_don']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Aucun don trouvé.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>
