<?php
session_start();
require 'config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $projet_id = $_POST['projet_id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $montant = $_POST['montant'];
    $commentaire = $_POST['commentaire'];
    $payment_method = $_POST['payment_method'];

    // Insérer le don dans la base de données
    $sql = "INSERT INTO dons (id_projet, nom, email, montant, commentaire, mode_paiement) VALUES (:projet_id, :nom, :email, :montant, :commentaire, :mode_paiement)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':projet_id', $projet_id);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':mode_paiement', $payment_method);
    $stmt->execute();

    // Redirection vers la page de paiement
    if ($payment_method == 'orange_money') {
        // Appel fictif à l'API Orange Money
        $orange_money_url = "https://api.orange.com/orange_money";
        // Remplacez par les paramètres réels de l'API Orange Money
        $params = [
            'amount' => $montant,
            'currency' => 'XOF',
            'externalId' => $projet_id,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => 'NUMERO_TELEPHONE_DONATEUR'
            ],
            'payerMessage' => 'Don pour le projet ' . $projet_id,
            'payeeNote' => 'Merci pour votre don'
        ];
        // Effectuer l'appel à l'API Orange Money
        // ...

        // Redirection vers la page de remerciement
        header('Location: merci.php');
        exit();
    } elseif ($payment_method == 'mtn_mobile_money') {
        // Appel fictif à l'API MTN Mobile Money
        $mtn_mobile_money_url = "https://api.mtn.com/mtn_mobile_money";
        // Remplacez par les paramètres réels de l'API MTN Mobile Money
        $params = [
            'amount' => $montant,
            'currency' => 'XOF',
            'externalId' => $projet_id,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => 'NUMERO_TELEPHONE_DONATEUR'
            ],
            'payerMessage' => 'Don pour le projet ' . $projet_id,
            'payeeNote' => 'Merci pour votre don'
        ];
        // Effectuer l'appel à l'API MTN Mobile Money
        // ...

        // Redirection vers la page de remerciement
        header('Location: merci.php');
        exit();
    } else {
        // Redirection après soumission
        header('Location: merci.php');
        exit();
    }
}
?>