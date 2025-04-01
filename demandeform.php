<?php  
require 'config.php'; // Connexion à la base de données  

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Récupérer les données  
    $type_demandeur = $_POST['type_demandeur'] ?? null;  
    $type_aide = $_POST['type_aide'] ?? null;  

    if (!$type_demandeur || !$type_aide) {  
        echo json_encode(["status" => "error", "message" => "Le type de demandeur et le type d'aide doivent être spécifiés."]);  
        exit;  
    }  

    try {  
        $uploadDir = 'uploads/';  
        if (!file_exists($uploadDir)) {  
            mkdir($uploadDir, 0777, true);  
        }  

        function handleFileUpload($fileInputName, $uploadDir) {  
            if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== 0) {  
                return null; // Retourner null si aucun fichier n'est téléchargé  
            }

            $fileInfo = pathinfo($_FILES[$fileInputName]['name']);  
            $fileExt = strtolower($fileInfo['extension']);  
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];  
            $finfo = new finfo(FILEINFO_MIME_TYPE);  
            $fileMimeType = $finfo->file($_FILES[$fileInputName]['tmp_name']);  

            if ($_FILES[$fileInputName]['size'] > 2097152 || !in_array($fileMimeType, $allowedMimeTypes) || !in_array($fileExt, ['jpg', 'jpeg', 'png', 'pdf'])) {  
                throw new Exception("Fichier non autorisé. Taille maximale: 2 Mo.");  
            }  

            $uniqueFileName = uniqid('', true) . '.' . $fileExt;  
            $destination = $uploadDir . $uniqueFileName;  
            if (!move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $destination)) {  
                throw new Exception("Erreur lors du téléchargement du fichier.");  
            }  
            return $destination;  
        }  

        // Initialiser $id_demunis à null
        $id_demunis = null;

        if ($type_demandeur == "Individu") {  
            $nom_individu = $_POST['nom_individu'] ?? '';  
            $age = $_POST['age'] ?? '';  
            $adresse_individu = $_POST['adresse_individu'] ?? '';  
            $telephone_individu = $_POST['telephone_individu'] ?? '';  
            $email_individu = $_POST['email_individu'] ?? '';  
            $raison_individu = $_POST['raison_individu'] ?? '';  

            $preuve_identite = handleFileUpload("preuve_identite", $uploadDir);  

            $sql = "INSERT INTO demunis (type_demandeur, nom_individu, age, adresse_individu, telephone_individu, email_individu, preuve_identite, raison_individu, type_aide)   
                    VALUES ('Individu', :nom_individu, :age, :adresse_individu, :telephone_individu, :email_individu, :preuve_identite, :raison_individu, :type_aide)";  
            $stmt = $pdo->prepare($sql);  
            $stmt->execute([  
                ':nom_individu' => $nom_individu,  
                ':age' => $age,  
                ':adresse_individu' => $adresse_individu,  
                ':telephone_individu' => $telephone_individu,  
                ':email_individu' => $email_individu,  
                ':preuve_identite' => $preuve_identite,  
                ':raison_individu' => $raison_individu,  
                ':type_aide' => $type_aide  
            ]);  

            // Récupérer l'ID de la dernière insertion dans la table demunis
            $id_demunis = $pdo->lastInsertId();
        } elseif ($type_demandeur == "Organisation") {  
            $nom_organisation = $_POST['nom_organisation'] ?? '';  
            $type_organisation = $_POST['type_organisation'] ?? '';  
            $adresse_organisation = $_POST['adresse_organisation'] ?? '';  
            $telephone_organisation = $_POST['telephone_organisation'] ?? '';  
            $email_organisation = $_POST['email_organisation'] ?? '';  
            $objectif_organisation = $_POST['objectif_organisation'] ?? '';  

            $documents_enregistrement = handleFileUpload("documents_enregistrement", $uploadDir);  

            $sql = "INSERT INTO demunis (type_demandeur, nom_organisation, type_organisation, adresse_organisation, telephone_organisation, email_organisation, documents_enregistrement, objectif_organisation, type_aide)   
                    VALUES ('Organisation', :nom_organisation, :type_organisation, :adresse_organisation, :telephone_organisation, :email_organisation, :documents_enregistrement, :objectif_organisation, :type_aide)";  
            $stmt = $pdo->prepare($sql);  
            $stmt->execute([  
                ':nom_organisation' => $nom_organisation,  
                ':type_organisation' => $type_organisation,  
                ':adresse_organisation' => $adresse_organisation,  
                ':telephone_organisation' => $telephone_organisation,  
                ':email_organisation' => $email_organisation,  
                ':documents_enregistrement' => $documents_enregistrement,  
                ':objectif_organisation' => $objectif_organisation,  
                ':type_aide' => $type_aide  
            ]);  

            // Récupérer l'ID de la dernière insertion dans la table demunis
            $id_demunis = $pdo->lastInsertId();
        }  

        // Vérifier si $id_demunis est défini avant d'insérer la notification
        if ($id_demunis === null) {
            throw new Exception("Erreur : Impossible de récupérer l'ID de la demande.");
        }

        // Insertion de la notification avec l'id_demunis
        $sql_notification = "INSERT INTO notifications (message, statut, date_creation, id_demunis) VALUES (:message, 'non_lu', NOW(), :id_demunis)";
        $stmt_notification = $pdo->prepare($sql_notification);
        $message = "Une nouvelle demande d'aide a été soumise par un(e) " . $type_demandeur;
        $stmt_notification->execute([
            ':message' => $message,
            ':id_demunis' => $id_demunis
        ]);

        echo json_encode(["status" => "success", "message" => "Demande soumise avec succès."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Erreur : " . $e->getMessage()]);
    }
}  
?>
