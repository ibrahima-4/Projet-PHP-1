<?php
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
include 'includes/header.php'; 
include 'includes/footer.php'; 
?>
