<?php
session_start();

// Vérifier si l'utilisateur est connecté et rediriger en fonction de son rôle
if (!isset($_SESSION['role'])) {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
    exit();
}

if ($_SESSION['role'] === 'Administrateur') {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/admin_dashboard.php"); // Rediriger vers le tableau de bord administrateur
} elseif ($_SESSION['role'] === 'bibliothecaire') {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/bibliothecaire_dashboard.php"); // Rediriger vers le tableau de bord bibliothécaire
} else {
    // Si le rôle n'est pas reconnu, déconnexion
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/deconnexion.php");
    exit();
}
