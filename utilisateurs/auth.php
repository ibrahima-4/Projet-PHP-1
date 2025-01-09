<?php
session_start();

function verifierConnexion() {
    if (!isset($_SESSION['utilisateur'])) {
        header('Location: ../utilisateurs/connexion.php');
        exit();
    }
}
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    // Redirige vers la page de connexion
    header('Location: ../utilisateurs/connexion.php');
    exit();
}
?>
