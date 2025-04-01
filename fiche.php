<?php  
require 'config.php';  

// Récupérer les notifications non lues
$sql = "SELECT * FROM notifications WHERE statut = 'non_lu' ORDER BY date_creation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si des notifications existent, récupérez les demandes d'aide liées
$demande_ids = array();
foreach ($notifications as $notif) {
    $demande_ids[] = $notif['id_demunis']; // Utilisez 'id_demunis' pour lier les notifications aux demandes
}

// Marquer les notifications comme lues dès qu'elles sont consultées
if (count($demande_ids) > 0) {
    $ids_placeholder = implode(',', $demande_ids);
    $update_sql = "UPDATE notifications SET statut = 'lu' WHERE id_demunis IN ($ids_placeholder)";
    $pdo->prepare($update_sql)->execute();
    
    // Récupérer les demandes d'aide liées aux notifications
    $sql_demande = "SELECT * FROM demunis WHERE id_demunis IN ($ids_placeholder)"; // Utilisez 'id_demunis' ici
    $stmt_demande = $pdo->prepare($sql_demande);
    $stmt_demande->execute();
    $demandes = $stmt_demande->fetchAll(PDO::FETCH_ASSOC);
} else {
    $demandes = [];
}
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Fiche des Demandes - NewHope</title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">  
    <style>  
        /* Ajout du style pour la page des demandes */
        body {  
            font-family: 'Arial', sans-serif;  
            background-color: #f4f4f9;  
        }

        .fiche-demande {  
            background-color: #fff;  
            border-radius: 8px;  
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);  
            margin-bottom: 20px;  
            padding: 20px;  
            transition: transform 0.3s ease;  
        }

        .fiche-demande:hover {  
            transform: scale(1.02);  
        }

        .fiche-demande .titre {  
            font-size: 1.5rem;  
            color: #ff6f61;  
            margin-bottom: 15px;  
        }

        .fiche-demande p {  
            font-size: 1rem;  
            color: #333;  
            margin-bottom: 10px;  
        }

        .fiche-demande .btn {  
            background-color: #ff6f61;  
            color: white;  
            border-radius: 5px;  
            padding: 10px 15px;  
            text-align: center;  
            font-size: 1rem;  
            border: none;  
        }

        .fiche-demande .btn:hover {  
            background-color: #ff4a40;  
        }

        .fiche-demande .file-link {  
            color: #007bff;  
            text-decoration: none;  
        }

        .fiche-demande .file-link:hover {  
            text-decoration: underline;  
        }

        .fiche-demande .file-container {  
            margin-top: 10px;  
        }

        .fiche-demande .file-container a {  
            margin-right: 10px;  
        }

        .fiche-demande .details {  
            background-color: #f9f9f9;  
            padding: 15px;  
            border-radius: 5px;  
        }

        .fiche-demande .details h5 {  
            font-size: 1.2rem;  
            color: #555;  
        }

        .fiche-demande .image-preview {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>  
</head>  
<body>  
<a href="dash.php" class="dashboard-btn" style="red;">Tableau de Bord</a>
    <div class="card">
    <div class="container mt-4">  
        <h2>Demandes d'Aide Notifiées</h2>  

        <?php if (count($demandes) > 0): ?>  
            <?php foreach ($demandes as $demande): ?>
                <div class="fiche-demande">
                    <div class="titre">
                        <strong>Type de Demandeur :</strong> <?php echo htmlspecialchars($demande['type_demandeur']); ?>
                    </div>
                    
                    <?php if ($demande['type_demandeur'] == 'Individu'): ?>
                        <div class="details">
                            <h5>Informations de l'Individu</h5>
                            <p><strong>Nom :</strong> <?php echo htmlspecialchars($demande['nom_individu']); ?></p>
                            <p><strong>Âge :</strong> <?php echo htmlspecialchars($demande['age']); ?></p>
                            <p><strong>Adresse :</strong> <?php echo htmlspecialchars($demande['adresse_individu']); ?></p>
                            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($demande['telephone_individu']); ?></p>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($demande['email_individu']); ?></p>
                            <p><strong>Raison de la demande :</strong> <?php echo htmlspecialchars($demande['raison_individu']); ?></p>
                        </div>
                        
                        <?php if ($demande['preuve_identite']): ?>
                            <div class="file-container">
                                <h5>Preuve d'identité :</h5>
                                <!-- Vérification du type d'image -->
                                <?php if (in_array(pathinfo($demande['preuve_identite'], PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg'])): ?>
                                    <img src="<?php echo htmlspecialchars($demande['preuve_identite']); ?>" alt="Preuve d'identité" class="image-preview">
                                <?php else: ?>
                                    <a href="<?php echo htmlspecialchars($demande['preuve_identite']); ?>" class="file-link" target="_blank">Voir la preuve d'identité</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($demande['type_demandeur'] == 'Organisation'): ?>
                        <div class="details">
                            <h5>Informations de l'Organisation</h5>
                            <p><strong>Nom de l'Organisation :</strong> <?php echo htmlspecialchars($demande['nom_organisation']); ?></p>
                            <p><strong>Type d'Organisation :</strong> <?php echo htmlspecialchars($demande['type_organisation']); ?></p>
                            <p><strong>Adresse :</strong> <?php echo htmlspecialchars($demande['adresse_organisation']); ?></p>
                            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($demande['telephone_organisation']); ?></p>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($demande['email_organisation']); ?></p>
                            <p><strong>Objectif de l'Organisation :</strong> <?php echo htmlspecialchars($demande['objectif_organisation']); ?></p>
                        </div>
                        
                        <?php if ($demande['documents_enregistrement']): ?>
                            <div class="file-container">
                                <h5>Documents d'enregistrement :</h5>
                                <a href="<?php echo htmlspecialchars($demande['documents_enregistrement']); ?>" class="file-link" target="_blank">Voir les documents d'enregistrement</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <p><strong>Type d'Aide Demandée :</strong> <?php echo htmlspecialchars($demande['type_aide']); ?></p>
                    <p><strong>Date de Soumission :</strong> <?php echo htmlspecialchars($demande['date_soumission']); ?></p>
                    
                    <!-- Boutons pour valider ou rejeter la demande -->
                    <div class="actions">
                        <a href="valider_demande.php?id=<?php echo $demande['id_demunis']; ?>&action=valider" class="btn btn-success">Valider</a>
                        <a href="#"  class="btn btn-danger" onclick="openRejetModal('<?php echo $demande['email_individu']; ?>', '<?php echo $demande['nom_individu']; ?>')">Rejeter</a>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune demande d'aide liée aux notifications.</p>
        <?php endif; ?>
    </div>  

    
    <!-- Modal de confirmation pour l'envoi du mail -->
<div class="modal fade" id="confirmRejetModal" tabindex="-1" aria-labelledby="confirmRejetLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmRejetLabel">Confirmer le rejet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous envoyer un mail au demandeur pour l'informer du rejet ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a id="sendMailBtn" href="#" class="btn btn-danger">Envoyer le mail</a>
            </div>
        </div>
    </div>
</div>
<!-- Chargement de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 

<script>
    // Fonction pour ouvrir le modal et préparer le lien mailto
    function openRejetModal(email, nom) {
        let mailtoLink = `mailto:${email}?subject=Rejet de votre demande&body=Bonjour ${nom},%0D%0A%0D%0A
        Nous vous informons que votre demande d'aide a été rejetée.%0D%0A
        Pour toute question, n'hésitez pas à nous contacter.%0D%0A%0D%0A
        Cordialement,%0D%0A
        L'équipe NewHope.`;

        // Modifier le lien du bouton dans le modal
        document.getElementById('sendMailBtn').href = mailtoLink;

        // Afficher le modal Bootstrap
        var myModal = new bootstrap.Modal(document.getElementById('confirmRejetModal'));
        myModal.show();
    }
</script>

 
</body>  
</html>