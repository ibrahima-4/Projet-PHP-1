<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    if (!isset($_SESSION['role'])) {
        header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
        exit();
    }
    $role = $_SESSION['role'];
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/Gestion-De-Bibliotheque/pages/dashboard.php">Bibliothèque</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if ($role === 'Administrateur'): ?>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_utilisateurs.php">Utilisateurs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/rapport_emprunts.php">Rapports</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/dashboard.php">Statistiques</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/utilisateurs/dashboard.php">Tableau de bord</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_emprunts.php">Emprunts</a></li>
                    <?php endif; ?>
                    
                    <?php if ($role === 'bibliothecaire' || $role === 'Administrateur'): ?>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_livres.php">Livres</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/pages/gestion_emprunts.php">Emprunts</a></li>
                    <?php endif; ?>
                    
                    <li class="nav-item"><a class="nav-link" href="/Gestion-De-Bibliotheque/utilisateurs/deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>