<?php  
session_start();
require 'config.php';  

// Vérifier si un administrateur est connecté et récupérer son rôle
if (!isset($_SESSION['role'])) {
    header('Location: login.php'); // Rediriger si non connecté
    exit();
}

$role = $_SESSION['role'];  // Récupérer le rôle de l'utilisateur
$nom = $_SESSION['nom'];  // Récupérer le nom de l'utilisateur

// Récupérer les notifications non lues  
$sql = "SELECT * FROM notifications WHERE statut = 'non_lu' ORDER BY date_creation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre de notifications non lues
$sql_count = "SELECT COUNT(*) FROM notifications WHERE statut = 'non_lu'";  
$stmt_count = $pdo->prepare($sql_count);  
$stmt_count->execute();  
$unread_count = $stmt_count->fetchColumn();  

// Récupérer les demandes validées pour l'administrateur des projets
$validated_requests = [];

    $sql_requests = "SELECT * FROM demunis WHERE statut = 'valide'";
    $stmt_requests = $pdo->prepare($sql_requests);
    $stmt_requests->execute();
    $validated_requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les dons pour l'administrateur des projets
$donations = [];
if ($role === 'Admin Projets') {
    $sql_donations = "SELECT * FROM dons"; 
    $stmt_donations = $pdo->prepare($sql_donations);
    $stmt_donations->execute();
    $donations = $stmt_donations->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les projets financés et à financer
$projets_finances = [];
$projets_a_financer = [];

    $sql_projets_finances = "SELECT * FROM projets WHERE statut = 'terminé'";
    $stmt_projets_finances = $pdo->prepare($sql_projets_finances);
    $stmt_projets_finances->execute();
    $projets_finances = $stmt_projets_finances->fetchAll(PDO::FETCH_ASSOC);

    $sql_projets_a_financer = "SELECT * FROM projets WHERE statut = 'en cours'";
    $stmt_projets_a_financer = $pdo->prepare($sql_projets_a_financer);
    $stmt_projets_a_financer->execute();
    $projets_a_financer = $stmt_projets_a_financer->fetchAll(PDO::FETCH_ASSOC);


// Récupérer les statistiques pour les cartes
$total_demandes = 0;
$total_projets = 0;
$total_donateurs = 0;


    $sql_total_demandes = "SELECT COUNT(*) FROM demunis";
    $stmt_total_demandes = $pdo->prepare($sql_total_demandes);
    $stmt_total_demandes->execute();
    $total_demandes = $stmt_total_demandes->fetchColumn();



    $sql_total_projets = "SELECT COUNT(*) FROM projets";
    $stmt_total_projets = $pdo->prepare($sql_total_projets);
    $stmt_total_projets->execute();
    $total_projets = $stmt_total_projets->fetchColumn();


    $sql_total_donateurs = "SELECT COUNT(*) FROM dons";
    $stmt_total_donateurs = $pdo->prepare($sql_total_donateurs);
    $stmt_total_donateurs->execute();
    $total_donateurs = $stmt_total_donateurs->fetchColumn();


?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Dashboard - NewHope</title>  
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
            font-size: 50px;  
            cursor: pointer;  
        }  

        .offcanvas {  
            background-color: #f5d4ce;  
            border-top-right-radius: 15px;  
            border-bottom-right-radius: 15px;  
        }  

        .offcanvas-body {  
            text-align: left;  
            padding: 20px;  
        }  

        .profile-container {  
            text-align: center;  
            padding: 20px 0;  
            background-color: transparent;  
            border-radius: 10px;  
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);  
        }  

        .profile-img {  
            width: 100px;  
            height: 100px;  
            border-radius: 50%;  
            object-fit: cover;  
            display: block;  
            margin: 0 auto 10px;  
            background-color: #f0dede;  
            position: relative;  
            border: 3px solid #ff6f61;  
            cursor: pointer;  
            font-size:50px;
        }  

        .list-group-item {  
            border: none;  
            padding: 12px 15px;  
            font-size: 16px;  
            transition: background 0.3s ease-in-out;  
            cursor: pointer;  
            color: #333;  
        }  

        .list-group-item:hover {  
            background-color: #fa9797;  
            border-radius: 8px;  
        }  

        .badge-notif {  
            position: absolute;  
            top: 0;  
            right: 0;  
            padding: 5px 10px;  
            border-radius: 50%;  
            background-color: red;  
            color: white;  
            font-size: 14px;  
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
            color: #ff6f61;  
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
    <!-- Navbar -->  
    <div class="topbar">  
        <div class="menu-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">  
            <i class="fas fa-bars"></i>  
        </div>  
        <div>
            <a href="arrow.php" class="btn btn-primary me-2"><i class="fas fa-home"></i> Aller sur le site</a> <!-- Lien pour aller sur le site -->
            <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </div>

    <!-- Offcanvas Menu -->  
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">  
        <div class="offcanvas-header">  
            <h5 class="offcanvas-title">NewHope</h5>  
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>  
        </div>  
        <div class="offcanvas-body">  
            <div class="profile-container">  
                <!-- Profil Image Container -->
                <div class="profile-img" id="profileContainer" onclick="triggerProfilePicChange()">
                    <i class="fas fa-user"></i> <!-- Icône utilisateur -->
                </div>
                <!-- Champ de téléchargement de fichier caché -->
                <form id="profilePicForm" action="upload_profile_pic.php" method="POST" enctype="multipart/form-data" style="display: none;">
                    <input type="file" id="uploadProfilePic" name="profile_pic" accept="image/*" onchange="submitProfilePicForm()">
                </form>
                
                <!-- Nom de l'utilisateur -->
                <h5><?php echo $nom; ?></h5>  <!-- Affichage du nom dynamique -->
                <!-- Affichage du rôle -->
                <p><?php echo ucfirst($role); ?></p>  <!-- Affichage du rôle dynamique -->
            </div>  
            <ul class="list-group mb-0">
    <?php if ($role === 'Super Admin'): ?>
        <li class="list-group-item"><i class="fas fa-users-cog"></i><a href="manage_admins.php" style="text-decoration:none;"> Gérer les administrateurs</a></li>
    <?php endif; ?>

    <?php if ($role === 'Admin Demandes'): ?>
        <li class="list-group-item"><i class="fas fa-phone" style="color:rgb(97, 49, 64);"></i><a href="contact_demandeur.php" style="text-decoration:none;"> Contacter le demandeur</a></li>
    <?php endif; ?>

    <?php if ($role === 'Admin Projets'): ?>
        <li class="list-group-item"><i class="fas fa-project-diagram"></i><a href="projet.php" style="text-decoration:none;"> Gérer les projets</a></li>
        <li class="list-group-item"><i class="fas fa-project-diagram"></i><a href="demande_validée.php" style="text-decoration:none;"> Consulter les demandes validées</a></li>
        <li class="list-group-item"><i class="fas fa-donate"></i><a href="consulter_don.php" class="text-decoration-none"> Consulter les dons</a></li>
    <?php endif; ?>

<?php if ($role !== 'Super Admin' && $role !== 'Admin projets'): ?>
    <li class="list-group-item">
        <i class="fas fa-bell" style="color:red;"></i>
        <a href="fiche.php" class="text-decoration-none"> Notifications</a>
    </li>
    <?php if ($unread_count > 0): ?>
        <span class="badge-notif"><?php echo $unread_count; ?></span>
    <?php endif; ?>
<?php endif; ?>

    <li class="list-group-item"><i class="fas fa-cog" style="color:green;"></i><a href="settings.php" style="text-decoration:none;"> Paramètres</a></li>
</ul>
        </div>  
    </div>  

    <div class="container mt-4">  
    <div class="row">  
        <!-- Carte Demandes -->
        <div class="col-md-4 mb-4">  
            <a href="detail_demandes.php" style="text-decoration:none;"> <!-- Lien vers la page des demandes -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-hand-holding-heart card-icon"></i>
                    <h5 class="card-title">Demandes</h5>  
                    <p class="card-text">Nombre total de demandes</p>
                    <h4><?php echo $total_demandes; ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-success" style="width: <?php echo ($total_demandes / 200) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  

        <!-- Carte Projets -->
        <div class="col-md-4 mb-4">  
            <a href="detail_projet.php" style="text-decoration:none;"> <!-- Lien vers la page des projets -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-project-diagram card-icon"></i>
                    <h5 class="card-title">Projets</h5>  
                    <p class="card-text">Nombre total de projets</p>
                    <h4><?php echo $total_projets; ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-info" style="width: <?php echo ($total_projets / 50) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  

        <!-- Carte Donateurs -->
        <div class="col-md-4 mb-4">  
            <a href="details_donateurs.php" style="text-decoration:none;"> <!-- Lien vers la page des donateurs -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-donate card-icon"></i>
                    <h5 class="card-title">Donateurs</h5>  
                    <p class="card-text">Nombre total de donateurs</p>
                    <h4><?php echo $total_donateurs; ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-warning" style="width: <?php echo ($total_donateurs / 100) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  

        <!-- Carte Projets Financés -->
        <div class="col-md-4 mb-4">  
            <a href="projets_finances.php" style="text-decoration:none;"> <!-- Lien vers la page des projets financés -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-hand-holding-usd card-icon"></i>
                    <h5 class="card-title">Projets financés</h5>  
                    <p class="card-text">Nombre total de projets financés</p>
                    <h4><?php echo count($projets_finances); ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-info" style="width: <?php echo (count($projets_finances) / 50) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  

        <!-- Carte Projets à Financer -->
        <div class="col-md-4 mb-4">  
            <a href="non_financer.php" style="text-decoration:none;"> <!-- Lien vers la page des projets à financer -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-hand-holding-usd card-icon"></i>
                    <h5 class="card-title">Projets à financer</h5>  
                    <p class="card-text">Nombre total de projets à financer</p>
                    <h4><?php echo count($projets_a_financer); ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-warning" style="width: <?php echo (count($projets_a_financer) / 50) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  

        <!-- Carte Dons Collectés -->
        <div class="col-md-4 mb-4">  
            <a href="consulter_don.php" style="text-decoration:none;"> <!-- Lien vers la page des dons collectés -->
                <div class="card p-3 text-center">  
                    <i class="fas fa-donate card-icon"></i>
                    <h5 class="card-title">Dons Collectés</h5>  
                    <p class="card-text">Nombre total de dons collectés</p>
                    <h4><?php echo count($donations); ?></h4>  
                    <div class="progress">  
                        <div class="progress-bar bg-danger" style="width: <?php echo (count($donations) / 50) * 100; ?>%"></div>  
                    </div>  
                </div>  
            </a>
        </div>  
    </div>  
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>  
    <script>
        // Fonction appelée lors du clic sur l'image du profil
        function triggerProfilePicChange() {
            const uploadInput = document.getElementById('uploadProfilePic');
            uploadInput.click();  // Ouvre la fenêtre de sélection de fichier
        }
    </script>
</body>  
</html>