<?php
session_start();
require 'config.php'; // Connexion à la base de données

if (isset($_POST['login'])) {
    // Récupérer les données du formulaire
    $firstname = $_POST['players_firstname'];
    $lastname = $_POST['players_name'];
    $password = $_POST['password'];

    // Vérifier dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM players WHERE players_firstname = ? AND players_name = ?");
    $stmt->execute([$firstname, $lastname]);
    $player = $stmt->fetch();

    // Vérifier le mot de passe et si l'utilisateur doit changer son mot de passe
    if ($player && password_verify($password, $player['password'])) {
        if ($player['must_change_password']) {
            $_SESSION['player_id'] = $player['players_id'];
            header("Location: change_password.php"); // Rediriger vers la page de changement de mot de passe
            exit();
        } else {
            $_SESSION['player_id'] = $player['players_id'];
            header("Location: dashboard.php"); // Rediriger vers le tableau de bord après connexion
            exit();
        }
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css"> <!-- Assure-toi que le fichier CSS est bien lié -->
</head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST" action="">
        <label for="players_firstname">Prénom :</label>
        <input type="text" name="players_firstname" required><br>

        <label for="players_name">Nom :</label>
        <input type="text" name="players_name" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Se connecter</button>
    </form>
</body>
</html>
