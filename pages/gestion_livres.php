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

// Traitement des actions (ajout, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'ajouter') {
        $titre = $_POST['titre'];
        $auteur = $_POST['auteur'];
        $editeur = $_POST['editeur'];
        $annee = $_POST['annee'];
        $quantite = $_POST['quantite'];
        
        try {
            $sql = "INSERT INTO livres (titre, auteur, editeur, annee, quantite) VALUES (:titre, :auteur, :editeur, :annee, :quantite)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':auteur' => $auteur,
                ':editeur' => $editeur,
                ':annee' => $annee,
                ':quantite' => $quantite,
            ]);
            $_SESSION['message'] = "Livre ajouté avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'supprimer') {
        $id = $_POST['id'];
        try {
            $sql = "DELETE FROM livres WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $_SESSION['message'] = "Livre supprimé avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    }
    header('Location: gestion_livres.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Notifications */
        .alert {
            animation: fadeIn 0.5s ease-in-out;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Formulaire et Boutons */
        .btn {
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        input, select {
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #007bff !important;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        /* Tableau */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Gestion des Livres</h1>

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

        <!-- Formulaire pour ajouter un livre -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="ajouter">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="auteur" class="form-control" placeholder="Auteur" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="editeur" class="form-control" placeholder="Éditeur" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="annee" class="form-control" placeholder="Année" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantite" class="form-control" placeholder="Quantité" required>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100">Ajouter le Livre</button>
                </div>
            </div>
        </form>

        <!-- Liste des livres -->
        <h2>Liste des Livres</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Éditeur</th>
                    <th>Année</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM livres";
                $stmt = $pdo->query($sql);
                foreach ($stmt as $livre): ?>
                    <tr>
                        <td><?= $livre['id']; ?></td>
                        <td><?= htmlspecialchars($livre['titre']); ?></td>
                        <td><?= htmlspecialchars($livre['auteur']); ?></td>
                        <td><?= htmlspecialchars($livre['editeur']); ?></td>
                        <td><?= htmlspecialchars($livre['annee']); ?></td>
                        <td><?= htmlspecialchars($livre['quantite']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?= $livre['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Suppression automatique des notifications après 5 secondes
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>