<?php
include '../includes/config.php';
include '../includes/functions.php';

// Vérification de la connexion
verifierConnexion();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
            body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 1rem;
        }
        button:hover {
            transform: translateY(-2px);
            transition: 0.2s;
        }
        .btn:hover {
        transform: translateY(-3px);
        transition: all 0.2s ease-in-out;
    }

    .alert {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo $_SESSION['utilisateur']['nom']; ?> !</h1>
        <p>Ceci est votre tableau de bord.</p>
        <a href="../utilisateurs/deconnexion.php" class="btn btn-danger">Se déconnecter</a>
    </div>
</body>
</html>
