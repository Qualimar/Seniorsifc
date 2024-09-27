<?php
session_start();
require 'config.php'; // Fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour récupérer l'utilisateur
    $query = $db->prepare('SELECT * FROM users WHERE username = :username');
    $query->execute(['username' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur est trouvé et que le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // Création de la session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirection après connexion réussie
        header('Location: index.php');
        exit();
    } else {
        $error = "Identifiant ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css"> <!-- Votre style.css -->
</head>
<body>

<div class="maindiv">
    <h1>Connexion</h1>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="username">Identifiant :</label>
        <input type="text" name="username" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required>
        
        <button type="submit">Connexion</button>
    </form>

    <a href="register.php">S'inscrire</a>
</div>

</body>
</html>
