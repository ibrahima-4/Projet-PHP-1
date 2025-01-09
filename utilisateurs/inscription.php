<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (:nom, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
    ]);

    echo "Utilisateur créé avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 2rem;
        }
        button:hover {
            transform: translateY(-3px);
            transition: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin">Administrateur</option>
                    <option value="bibliothecaire">Bibliothécaire</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer un compte</button>
        </form>
    </div>
    <form action="../utilisateurs/deconnexion.php" method="post">
   <div style="text-align: center;"> <button type="submit" class="btn btn-danger">Se déconnecter</button></div>
</form>
</body>
</html>
