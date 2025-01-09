<?php
session_start();
session_unset();
session_destroy();
header("Location: /Gestion-De-Bibliotheque/utilisateurs/connexion.php");
exit();
