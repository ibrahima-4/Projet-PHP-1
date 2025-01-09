<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur d'Accès</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-page {
            text-align: center;
            margin-top: 100px;
        }

        .error-page h1 {
            font-size: 4em;
            color: #dc3545;
        }

        .error-page p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <h1>Accès Interdit</h1>
        <p>Vous n'avez pas la permission d'accéder à cette page.</p>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bouton Animé</title>
    <style>
        /* Style pour le div */
        #animatedDiv {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Style pour le bouton */
        .animated-button {
            background-color: #007bff; /* Couleur bleu */
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Effet hover */
        .animated-button:hover {
            transform: scale(1.1); /* Agrandit légèrement */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Ajoute une ombre */
        }

        /* Effet active */
        .animated-button:active {
            transform: scale(1); /* Réduit l'effet */
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div id="animatedDiv">
        <a href="/Gestion-De-Bibliotheque/utilisateurs/connexion.php">
            <button class="animated-button" id="magicButton">Reconnectez-vous</button>
        </a>
    </div>

    <script>
        // Animation simple en JavaScript
        const button = document.getElementById('magicButton');

        button.addEventListener('mouseenter', () => {
            button.style.backgroundColor = '#0056b3'; // Couleur plus foncée au survol
        });

        button.addEventListener('mouseleave', () => {
            button.style.backgroundColor = '#007bff'; // Retour à la couleur normale
        });

        // Ajoute un effet de "rebond" au clic
        button.addEventListener('click', () => {
            button.style.transform = 'scale(0.9)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
        });
    </script>
</body>
</html>
    </div>
</body>
</html>
