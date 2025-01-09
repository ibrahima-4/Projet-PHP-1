<?php
require_once '../includes/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === 'ajouter') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $password]);
        echo json_encode(['type' => 'success', 'message' => 'Utilisateur ajouté avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['type' => 'danger', 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} elseif ($action === 'lister') {
    $utilisateurs = $pdo->query("SELECT id, nom, email FROM utilisateurs")->fetchAll();
    echo json_encode($utilisateurs);
} elseif ($action === 'supprimer') {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['type' => 'success', 'message' => 'Utilisateur supprimé avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['type' => 'danger', 'message' => 'Erreur : ' . $e->getMessage()]);
    }
}