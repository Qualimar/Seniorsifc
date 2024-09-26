<?php
session_start();
require 'config.php'; // Connexion à la base de données

if (!isset($_SESSION['player_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    
    // Mettre à jour le mot de passe dans la base de données et désactiver le flag must_change_password
    $stmt = $pdo->prepare("UPDATE players SET password = ?, must_change_password = FALSE WHERE players_id = ?");
    $stmt->execute([$new_password, $_SESSION['player_id']]);
    
    header("Location: dashboard.php"); // Rediriger vers le tableau de bord après la mise à jour
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Changer votre mot de passe</h1>
    <form method="POST" action="">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" required><br>

        <button type="submit" name="change_password">Mettre à jour</button>
    </form>
</body>
</html>
