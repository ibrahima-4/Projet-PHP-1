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

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'ajouter') {
        // Ajouter un utilisateur
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO utilisateurs (nom, email, role, password) VALUES (:nom, :email, :role, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':role' => $role,
                ':password' => $password,
            ]);
            $_SESSION['message'] = "Utilisateur ajouté avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'modifier') {
        // Modifier un utilisateur
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        try {
            $sql = "UPDATE utilisateurs SET nom = :nom, email = :email, role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':role' => $role,
                ':id' => $id,
            ]);
            $_SESSION['message'] = "Utilisateur modifié avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    } elseif ($action === 'supprimer') {
        // Supprimer un utilisateur
        $id = $_POST['id'];

        try {
            $sql = "DELETE FROM utilisateurs WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $_SESSION['message'] = "Utilisateur supprimé avec succès !";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    }

    header('Location: gestion_utilisateurs.php'); // Rediriger après action
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
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
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h1>Gestion des Utilisateurs</h1>

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

        <!-- Formulaire d'ajout d'utilisateur -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="ajouter"><br><br>
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" required>
                </div>
                <div class="col-md-4">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select" required>
                        <option value="">Rôle</option>
                        <option value="Administrateur">Administrateur</option>
                        <option value="bibliothecaire">Bibliothécaire</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </div>
            </div>
        </form>

        <!-- Liste des utilisateurs -->
        <h2>Liste des Utilisateurs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $utilisateurs = $pdo->query("SELECT * FROM utilisateurs")->fetchAll();
                foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= $u['id']; ?></td>
                        <td><?= htmlspecialchars($u['nom']); ?></td>
                        <td><?= htmlspecialchars($u['email']); ?></td>
                        <td><?= htmlspecialchars($u['role']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="modifier">
                                <input type="hidden" name="id" value="<?= $u['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Modifier</button>
                            </form>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?= $u['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
