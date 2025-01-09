CREATE DATABASE gestion_bibliotheque;
use gestion_bibliotheque;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('Administrateur', 'bibliothecaire') NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    editeur VARCHAR(255) NOT NULL,
    annee INT NOT NULL,
    quantite INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE emprunts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    livre_id INT,
    date_emprunt DATE,
    date_retour DATE,
    penalite DECIMAL(10, 2),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (livre_id) REFERENCES livres(id)
);
