<?php
session_start();

// Vérification de la session et du rôle
if (!isset($_SESSION['role'])) {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
    exit();
}

$role = $_SESSION['role']; // Récupérer le rôle pour l'affichage conditionnel
if ($role !== 'Administrateur') {
    // Si l'utilisateur n'est pas administrateur, rediriger vers une autre page
    header("Location: /Gestion-De-Bibliotheque/pages/dashboard.php");
    exit();
}

require_once '../includes/config.php'; // Connexion à la base de données
// Rapport des emprunts actifs
$empruntsActifs = $pdo->query("
    SELECT u.nom AS utilisateur, u.email, l.titre AS livre, e.date_emprunt
    FROM emprunts e
    JOIN utilisateurs u ON e.utilisateur_id = u.id
    JOIN livres l ON e.livre_id = l.id
    WHERE e.date_retour IS NULL
")->fetchAll(PDO::FETCH_ASSOC);

// Rapport des livres non disponibles
$livresNonDisponibles = $pdo->query("
    SELECT l.titre, l.quantite, COUNT(e.id) AS emprunts
    FROM livres l
    LEFT JOIN emprunts e ON l.id = e.livre_id AND e.date_retour IS NULL
    GROUP BY l.id
    HAVING l.quantite = 0 OR COUNT(e.id) >= l.quantite
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Emprunts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            margin-top: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }
        table tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; // Inclure le header ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Rapports des Emprunts et Livres</h1>

        <!-- Rapport des emprunts actifs -->
        <h2>Emprunts Actifs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Livre</th>
                    <th>Date d'Emprunt</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($empruntsActifs) > 0): ?>
                    <?php foreach ($empruntsActifs as $emprunt): ?>
                        <tr>
                            <td><?= htmlspecialchars($emprunt['utilisateur']); ?></td>
                            <td><?= htmlspecialchars($emprunt['email']); ?></td>
                            <td><?= htmlspecialchars($emprunt['livre']); ?></td>
                            <td><?= htmlspecialchars($emprunt['date_emprunt']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun emprunt actif pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Rapport des livres non disponibles -->
        <h2>Livres Non Disponibles</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Quantité</th>
                    <th>Emprunts Actifs</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($livresNonDisponibles) > 0): ?>
                    <?php foreach ($livresNonDisponibles as $livre): ?>
                        <tr>
                            <td><?= htmlspecialchars($livre['titre']); ?></td>
                            <td><?= htmlspecialchars($livre['quantite']); ?></td>
                            <td><?= htmlspecialchars($livre['emprunts']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Tous les livres sont disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="../utilisateurs/admin_dashboard.php" class="btn btn-primary mt-3">Retour au Tableau de Bord</a>
    </div>

    <?php include '../includes/footer.php'; // Inclure le footer ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
