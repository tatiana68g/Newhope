<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire un don</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
        }
        .container {
            max-width: 450px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(216, 209, 209, 0.1);
        }
        .card-header {
            background-color:rgb(240, 77, 28);
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

    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-donate"></i> Faire un don</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="traiter_don.php">
                <input type="hidden" name="projet_id" value="<?php echo htmlspecialchars($_GET['projet_id']); ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                    
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" id="montant" name="montant" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="commentaire" class="form-label">Commentaire</label>
                    <textarea id="commentaire" name="commentaire" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="payment" class="form-label">Mode de paiement</label>
                    <div class="form-radio">
                        <div class="radio">
                            <label>
                                <input type="radio" name="payment_method" value="orange_money" checked>
                                <span class="checkmark"></span>
                                <span class="fill-control-description">Orange Money</span>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="payment_method" value="mtn_mobile_money">
                                <span class="checkmark"></span>
                                <span class="fill-control-description">MTN Mobile Money</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Soumettre</button>
            </form>
        </div>
    </div>
</div>
 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>