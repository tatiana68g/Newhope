<?php
require 'config.php';

// Vérification de la catégorie sélectionnée par l'utilisateur
$type_aide = isset($_GET['type_aide']) ? $_GET['type_aide'] : '';

// Définition du seuil pour les projets récents (7 jours)
$seuil_recent = date('Y-m-d H:i:s', strtotime('-7 days'));

// Requête SQL pour récupérer les projets en fonction du type d'aide sélectionné
if ($type_aide) {
    $sql = "SELECT * FROM projets WHERE type_aide = :type_aide ORDER BY date_creation DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':type_aide', $type_aide, PDO::PARAM_STR);
} else {
    // Si aucun type n'est sélectionné, récupérer tous les projets
    $sql = "SELECT * FROM projets ORDER BY date_creation DESC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets publiés - NewHope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(145deg, rgb(242, 237, 239), rgb(237, 237, 238));
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .container {
            max-width: 1900px;
            margin-top: 30px;
        }
        
        .card {
            border-radius: 12px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            height: 100%;
            color: black;
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .card-img-top {
            width: 100%;
            height: 230px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: #eee;
        }
        .badge {
            font-size: 1rem;
            padding: 5px 10px;
        }
        .don-btn {
            background: rgb(245, 67, 67);
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            display: block;
            transition: background 0.3s ease;
        }
        .don-btn:hover {
            background: rgb(144, 162, 236);
            color: white;
        }
        .date-info {
            font-size: 0.9rem;
            color: black;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	<div class="container">
	<a class="navbar-brand" href="arrow.php" style="font-size: 2.5em; font-family: 'Montserrat', sans-serif; background: linear-gradient(90deg, #ff7e5f, #feb47b); -webkit-background-clip: text; color: transparent; text-transform: uppercase; font-weight: 700; letter-spacing: 3px; font-style: italic; transition: color 0.3s ease;">
NewHope
</a>


		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="oi oi-menu"></span> Menu
		</button style="font-size:1.5em;" >
<!-- Navbar -->
<div class="collapse navbar-collapse" id="ftco-nav">
<ul class="navbar-nav ml-auto">
	<li class="nav-item active"><a href="arrow.php" class="nav-link" style="font-size:2em;"> <i class="fas fa-home"></i>Acceuil</a></li>
	<li class="nav-item"><a href="A_propos.html" class="nav-link" style="font-size:2em;"><i class="fas fa-info-circle"></i>À Propos</a></li>


	<!-- Dropdown pour Connexion / Inscription -->
	<li class="nav-item dropdown">
		<a class="nav-link " href="login.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		   <p style="font-size:2em;"><i class="fas fa-info-circle"></i> Admin</p>
		</a>
		

	<li class="nav-item cta"><a href="" class="nav-link"  style="font-size:2em;"><i class="fas fa-hand-holding-heart"></i>Donner</a></li>
</ul>
</div>


</nav> 
<!-- END nav -->
 
<section class="hero-wrap js-fullheight">
<div class="home-slider js-fullheight owl-carousel">
	<div class="slider-item js-fullheight" style="background-image:url(images/bg_1.jpg);">
		<div class="overlay-1"></div><div class="overlay-2"></div><div class="overlay-3"></div><div class="overlay-4"></div>
		<div class="container">
			<div class="row no-gutters slider-text js-fullheight align-items-center">
				<div class="col-md-10 col-lg-7 ftco-animate">
					<div class="text w-100">
						<h2>Agissez maintenant</h2>
						<h1 class="mb-3">Soutenez un projet et changez une vie</h1>
						<div class="d-flex meta">
							<div class=""><p class="mb-0"><a href="#" class="btn btn-secondary py-3 px-2 px-md-4">Faire un don</a></p></div>
							<a href="#" class="d-flex align-items-center button-link">
								<div class="button-video d-flex align-items-center justify-content-center">
									<span class="fa fa-play"></span>
								</div>
								<span>Voir comment aider</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="slider-item js-fullheight" style="background-image:url(images/bg_2.jpg);">
		<div class="overlay-1"></div><div class="overlay-2"></div><div class="overlay-3"></div><div class="overlay-4"></div>
		<div class="container">
			<div class="row no-gutters slider-text js-fullheight align-items-center">
				<div class="col-md-10 col-lg-7 ftco-animate">
					<div class="text w-100">
						<h2>Faites la différence</h2>
						<h1 class="mb-3">Contribuez à un projet d'entraide</h1>
						<div class="d-flex meta">
							<div class=""><p class="mb-0"><a href="#" class="btn btn-secondary py-3 px-2 px-md-4">Faire un don</a></p></div>
							<a href="#" class="d-flex align-items-center button-link">
								<div class="button-video d-flex align-items-center justify-content-center">
									<span class="fa fa-play"></span>
								</div>
								<span>En savoir plus</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="slider-item js-fullheight" style="background-image:url(images/bg_3.jpg);">
		<div class="overlay-1"></div><div class="overlay-2"></div><div class="overlay-3"></div><div class="overlay-4"></div>
		<div class="container">
			<div class="row no-gutters slider-text js-fullheight align-items-center">
				<div class="col-md-10 col-lg-7 ftco-animate">
					<div class="text w-100">
						<h2>Un petit geste, un grand impact</h2>
						<h1 class="mb-3">Aidez-nous à réaliser des projets solidaires</h1>
						<div class="d-flex meta">
							<div class=""><p class="mb-0"><a href="#" class="btn btn-secondary py-3 px-2 px-md-4">Faire un don</a></p></div>
							<a href="#" class="d-flex align-items-center button-link">
								<div class="button-video d-flex align-items-center justify-content-center">
									<span class="fa fa-play"></span>
								</div>
								<span>Voir l'impact de votre aide</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<section class="hero-wrap js-fullheight">
    <div class="container">
        <h1 class="text-center my-4"><i class="fas fa-hands-helping" style="color: red;"></i> Nos Projets d'Aide</h1>

        <!-- Projets Récents -->
        <h2 class="mt-4"><u> Projets d'aide récents</u></h2>
        <br><br>
        <div class="row">
            <?php foreach ($projets as $projet): ?>
                <?php if ($projet['date_creation'] >= $seuil_recent): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <?php 
                                $imagePath = !empty($projet['images']) ? explode(',', $projet['images'])[0] : 'default.png';
                                if (!file_exists($imagePath) || empty($projet['images'])) {
                                    $imagePath = 'default.png';
                                }
                            ?>
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="Image du projet">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($projet['titre']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($projet['description'], 0, 255))) . '...'; ?></p>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($projet['type_aide']); ?></span>
                                <span class="badge bg-<?php echo ($projet['statut'] === 'en cours') ? 'success' : (($projet['statut'] === 'rejeté') ? 'danger' : 'info'); ?>"><?php echo ucfirst($projet['statut']); ?></span>
                                <p class="date-info"><i class="fas fa-calendar-alt"></i> Créé le : <?php echo date('d/m/Y H:i', strtotime($projet['date_creation'])); ?></p>
                                <p class="date-info"><i class="fas fa-clock"></i> Délai : <?php echo htmlspecialchars($projet['delais']); ?> jours</p>
                                <a href="donate.php?projet_id=<?php echo $projet['id_projet']; ?>" class="don-btn mt-3">Faire un don</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <a href="index.php" class="btn btn-danger mt-4"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
