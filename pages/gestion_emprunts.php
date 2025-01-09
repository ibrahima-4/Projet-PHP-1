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
// Gestion des actions (ajout emprunt, retour livre)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'ajouter') {
        $utilisateur_id = $_POST['utilisateur_id'];
        $livre_id = $_POST['livre_id'];
        $date_emprunt = date('Y-m-d');

        try {
            $sql = "INSERT INTO emprunts (utilisateur_id, livre_id, date_emprunt) VALUES (:utilisateur_id, :livre_id, :date_emprunt)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':utilisateur_id' => $utilisateur_id,
                ':livre_id' => $livre_id,
                ':date_emprunt' => $date_emprunt,
            ]);
            $_SESSION['message'] = "Emprunt ajouté avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'retour') {
        $id = $_POST['id'];
        $date_retour = date('Y-m-d');
        $penalite = 0;

        try {
            // Calcul de la pénalité
            $sql = "SELECT DATEDIFF(:date_retour, date_emprunt) AS jours FROM emprunts WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':date_retour' => $date_retour,
                ':id' => $id,
            ]);
            $jours = $stmt->fetchColumn();

            if ($jours > 14) { // Supposons que la limite d'emprunt est de 14 jours
                $penalite = ($jours - 14) * 0.50; // 0.50€ par jour de retard
            }

            // Mise à jour de l'emprunt
            $sql = "UPDATE emprunts SET date_retour = :date_retour, penalite = :penalite WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':date_retour' => $date_retour,
                ':penalite' => $penalite,
                ':id' => $id,
            ]);

            $_SESSION['message'] = "Retour enregistré avec succès ! Pénalité : {$penalite}€";
            $_SESSION['message_type'] = "info";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    }

    header('Location: gestion_emprunts.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emprunts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <?php  include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1>Gestion des Emprunts</h1>

        <!-- Notifications -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <!-- Formulaire pour ajouter un emprunt -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="ajouter">
            <div class="row g-3">
                <div class="col-md-6">
                    <select name="utilisateur_id" class="form-select" required>
                        <option value="">Sélectionnez un utilisateur</option>
                        <?php
                        $utilisateurs = $pdo->query("SELECT id, nom FROM utilisateurs")->fetchAll();
                        foreach ($utilisateurs as $u) {
                            echo "<option value='{$u['id']}'>{$u['nom']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="livre_id" class="form-select" required>
                        <option value="">Sélectionnez un livre</option>
                        <?php
                        $livres = $pdo->query("SELECT id, titre FROM livres")->fetchAll();
                        foreach ($livres as $l) {
                            echo "<option value='{$l['id']}'>{$l['titre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100">Ajouter l'Emprunt</button>
                </div>
            </div>
        </form>

        <!-- Liste des emprunts -->
        <h2>Emprunts Actifs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date d'Emprunt</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT e.id, u.nom AS utilisateur, l.titre AS livre, e.date_emprunt
                        FROM emprunts e
                        JOIN utilisateurs u ON e.utilisateur_id = u.id
                        JOIN livres l ON e.livre_id = l.id
                        WHERE e.date_retour IS NULL";
                $stmt = $pdo->query($sql);
                foreach ($stmt as $emprunt): ?>
                    <tr>
                        <td><?= $emprunt['id']; ?></td>
                        <td><?= htmlspecialchars($emprunt['utilisateur']); ?></td>
                        <td><?= htmlspecialchars($emprunt['livre']); ?></td>
                        <td><?= htmlspecialchars($emprunt['date_emprunt']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="retour">
                                <input type="hidden" name="id" value="<?= $emprunt['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Retour</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
               
                include '../includes/footer.php'; 
            ?>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
