<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe
    $email = $_POST['email'];
    $players_id = $_POST['players_id']; // Sélection du joueur

    // Définir une image par défaut pour le profil s'il n'y en a pas
    $profile_pic = 'uploads/default_profile.png';

    // Vérification si le nom d'utilisateur ou l'email existe déjà
    $check_query = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $check_query->execute(['username' => $username, 'email' => $email]);

    if ($check_query->rowCount() > 0) {
        $error = "L'identifiant ou l'email est déjà pris. Veuillez en choisir un autre.";
    } else {
        // Insertion dans la BDD
        $query = $db->prepare('INSERT INTO users (username, password, email, players_id, profile_pic) VALUES (:username, :password, :email, :players_id, :profile_pic)');
        
        if ($query->execute([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'players_id' => $players_id,
            'profile_pic' => $profile_pic
        ])) {
            // Redirection après l'inscription réussie
            header('Location: login.php');
            exit();
        } else {
            $error = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="maindiv">
    <h1>Inscription</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="register.php">
        <label for="username">Identifiant :</label>
        <input type="text" name="username" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required>
        
        <label for="email">Email :</label>
        <input type="email" name="email">
        
        <label for="players_id">Choisir un joueur :</label>
        <select name="players_id" required>
            <?php
            // Récupérer tous les joueurs disponibles pour lier à l'utilisateur
            $players = $db->query("SELECT players_id, players_firstname, players_name FROM players");
            while ($player = $players->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $player['players_id'] . '">' . $player['players_firstname'] . ' ' . $player['players_name'] . '</option>';
            }
            ?>
        </select>
        
        <button type="submit">S'inscrire</button>
    </form>

    <a href="login.php">Déjà inscrit ? Se connecter ici</a>
</div>

</body>
</html>
