<?php
session_start();
require_once '../includes/config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Administrateur') {
                header('Location: /Gestion-De-Bibliotheque/utilisateurs/admin_dashboard.php');
            } elseif ($user['role'] === 'bibliothecaire') {
                header('Location: /Gestion-De-Bibliotheque/utilisateurs/bibliothecaire_dashboard.php');
            }
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6c63ff, #c37bff);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .welcome-banner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            width: 100%;
            max-width: 600px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            animation: fadeIn 2s;
        }
        .welcome-banner h1 {
            color: white;
            font-family: 'Arial', sans-serif;
            font-size: 22px;
            text-align: center;
            animation: slideDown 2s;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }
        .card h2 {
            color: #6c63ff;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #6c63ff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #574bfc;
        }
        img.logo {
            width: 100px;
            height: auto;
            display: block;
            margin: 0 auto 20px auto;
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
        @keyframes slideDown {
            from {
                transform: translateY(-50px);
            }
            to {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="welcome-banner">
        <h1>Bienvenue sur la plateforme de gestion de la bibliothèque !<br>Gérez vos livres et utilisateurs en toute simplicité.<br> Bonne navigation</h1>
    </div>

    <div class="card">
        <img src="../img/logo.avif" alt="Logo" class="logo">
        <h2 class="text-center mb-4">Connexion</h2>
        <?php if (isset($erreur)): ?>
            <div class="alert alert-danger"><?php echo $erreur; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            
        </form>
    </div>

    <script>
        // Effets visuels sur les champs de saisie
        document.querySelectorAll("input").forEach(input => {
            input.addEventListener("focus", () => {
                input.style.boxShadow = "0 0 8px #6c63ff";
                input.style.transition = "0.3s";
            });
            input.addEventListener("blur", () => {
                input.style.boxShadow = "none";
            });
        });
    </script>
     <div style="color: black;"><?php include '../includes/footer.php'; ?></div>
    
</body>
</html>