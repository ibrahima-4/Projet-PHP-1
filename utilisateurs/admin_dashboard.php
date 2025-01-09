<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bibliothèque - Administrateur</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_utilisateurs.php">Utilisateurs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/rapport_emprunts.php">Rapports</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/dashboard.php">Statistiques</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_emprunts.php">Emprunts</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_livres.php">Livres</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_emprunts.php">Emprunts</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/utilisateurs/deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Tableau de Bord Administrateur</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['nom']); ?>.</p>
    </div>
</body>
</html>
