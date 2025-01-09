<?php
session_start();

// Vérification de la session et du rôle
if (!isset($_SESSION['role'])) {
    header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
    exit();
}

include '../includes/header.php'; 
require_once '../includes/config.php'; // Connexion à la base de données
// Calcul des statistiques
try {
    // Nombre total d'utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) AS total_utilisateurs FROM utilisateurs");
    $total_utilisateurs = $stmt->fetch()['total_utilisateurs'];

    // Nombre total de livres
    $stmt = $pdo->query("SELECT COUNT(*) AS total_livres FROM livres");
    $total_livres = $stmt->fetch()['total_livres'];

    // Nombre total d'emprunts actifs
    $stmt = $pdo->query("SELECT COUNT(*) AS emprunts_actifs FROM emprunts WHERE date_retour IS NULL");
    $emprunts_actifs = $stmt->fetch()['emprunts_actifs'];

    // Nombre d'emprunts en retard
    $stmt = $pdo->query("SELECT COUNT(*) AS emprunts_retard FROM emprunts WHERE date_retour IS NULL AND DATEDIFF(NOW(), date_emprunt) > 14");
    $emprunts_retard = $stmt->fetch()['emprunts_retard'];

    // Livres disponibles vs non disponibles
    $stmt = $pdo->query("SELECT SUM(quantite) AS total_disponibles FROM livres");
    $livres_disponibles = $stmt->fetch()['total_disponibles'];
    $livres_non_disponibles = $total_livres - $livres_disponibles;
} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Tableau de bord - Statistiques</h1>
    
    <!-- Statistiques sous forme de cartes -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="p-3 bg-primary text-white stat-card text-center">
                <h3><?= $total_utilisateurs ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-success text-white stat-card text-center">
                <h3><?= $total_livres ?></h3>
                <p>Livres</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-warning text-white stat-card text-center">
                <h3><?= $emprunts_actifs ?></h3>
                <p>Emprunts Actifs</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-danger text-white stat-card text-center">
                <h3><?= $emprunts_retard ?></h3>
                <p>Emprunts en Retard</p>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="chart-container">
                <canvas id="livresChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <canvas id="empruntsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Graphique des livres (disponibles vs non disponibles)
    const livresCtx = document.getElementById('livresChart').getContext('2d');
    new Chart(livresCtx, {
        type: 'pie',
        data: {
            labels: ['Disponibles', 'Non Disponibles'],
            datasets: [{
                data: [<?= $livres_disponibles ?>, <?= $livres_non_disponibles ?>],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Graphique des emprunts (actifs vs retard)
    const empruntsCtx = document.getElementById('empruntsChart').getContext('2d');
    new Chart(empruntsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'En Retard'],
            datasets: [{
                data: [<?= $emprunts_actifs ?>, <?= $emprunts_retard ?>],
                backgroundColor: ['#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
<?php  require_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
