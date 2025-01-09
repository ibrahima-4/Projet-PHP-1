<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'bibliothecaire') {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Bibliothécaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header spécifique pour le bibliothécaire -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/Gestion-De-Bibliotheque/pages/bibliothecaire_dashboard.php">Bibliothèque</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_livres.php">Livres et emrunts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Gestion-De-Bibliotheque/utilisateurs/deconnexion.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Tableau de Bord Bibliothécaire</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['nom']); ?>.</p>
        <p>Utilisez le menu ci-dessus pour gérer les livres et les emprunts.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
