<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit();
}
function verifierConnexion() {
    if (!isset($_SESSION['utilisateur'])) {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header('Location: ../utilisateurs/connexion.php');
        exit();
    }
}
?>
