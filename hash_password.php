<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasher un mot de passe</title>
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
        <h2>Hashage de mot de passe</h2>
        <p>Entrez un mot de passe pour générer son hash :</p>
        <form method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="text" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Générer le hash</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];

            // Génération du hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            echo "<div class='alert alert-success mt-3'>";
            echo "<strong>Mot de passe hashé :</strong> <br> $hashed_password";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>