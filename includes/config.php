<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_bibliotheque'; // NOM DE LA BASE
$username = 'root'; // Nom d'utilisateur par défaut pour XAMPP
$password = ''; // Mot de passe par défaut pour XAMPP (vide)

// Initialisation de la connexion PDO
try {
    // Création d'une instance PDO avec des options sécurisées
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", // Ajout de l'encodage UTF-8
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs en mode Exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Récupération des résultats en tableau associatif
            PDO::ATTR_EMULATE_PREPARES => false, // Désactive l'émulation des requêtes préparées pour plus de sécurité
        ]
    );
} catch (PDOException $e) {
    // Affichage d'un message d'erreur convivial et arrêt du script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}