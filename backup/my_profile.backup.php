<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

session_start();
require 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérer les informations du joueur et de l'utilisateur
$query = $db->prepare("
    SELECT p.players_firstname, p.players_name, p.age, p.height, p.weight, p.preferred_position, p.nickname, u.profile_pic
    FROM players p
    JOIN users u ON u.players_id = p.players_id
    WHERE u.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);
$user_info = $query->fetch(PDO::FETCH_ASSOC);

// Traitement de la mise à jour des informations du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les nouvelles valeurs soumises par le formulaire
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $preferred_position = $_POST['preferred_position'];
    $nickname = $_POST['nickname'];

    // Mise à jour des informations du joueur
    $update_query = $db->prepare("
        UPDATE players 
        SET age = :age, height = :height, weight = :weight, preferred_position = :preferred_position, nickname = :nickname
        WHERE players_id = (SELECT players_id FROM users WHERE user_id = :user_id)
    ");
    $update_query->execute([
        'age' => $age,
        'height' => $height,
        'weight' => $weight,
        'preferred_position' => $preferred_position,
        'nickname' => $nickname,
        'user_id' => $user_id
    ]);

    // Vérifier si un fichier de photo de profil a été téléchargé
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic = 'uploads/' . basename($_FILES['profile_pic']['name']);

        // Déplacer le fichier téléchargé vers le dossier uploads
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic)) {
            // Mettre à jour la table users avec la nouvelle photo
            $update_user_query = $db->prepare("UPDATE users SET profile_pic = :profile_pic WHERE user_id = :user_id");
            $update_user_query->execute(['profile_pic' => $profile_pic, 'user_id' => $user_id]);
        } else {
            echo "<p>Erreur lors du téléchargement de la photo.</p>";
        }
    }

    // Recharger la page après la mise à jour
    header("Location: my_profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>MON PROFIL</h1>

<!-- Lien pour retourner à l'index -->
<p><a href="index.php" class="nav-link">Retour à l'accueil</a></p>

<div class="profile-container">
    <h2><?php echo $user_info['players_firstname'] . ' ' . $user_info['players_name']; ?></h2>
    
    <!-- Affichage de la photo de profil -->
    <?php if (!empty($user_info['profile_pic'])): ?>
        <img src="<?php echo $user_info['profile_pic']; ?>" alt="Photo de profil" width="150" height="150">
    <?php else: ?>
        <img src="uploads/default_profile.png" alt="Photo de profil par défaut" width="150" height="150">
    <?php endif; ?>

    <!-- Formulaire de mise à jour des informations -->
    <form action="my_profile.php" method="post" enctype="multipart/form-data">
        <p>
            <label>Âge :</label>
            <input type="number" name="age" value="<?php echo $user_info['age']; ?>" required>
        </p>
        <p>
            <label>Taille (en cm) :</label>
            <input type="number" name="height" value="<?php echo $user_info['height']; ?>" required>
        </p>
        <p>
            <label>Poids (en kg) :</label>
            <input type="number" name="weight" value="<?php echo $user_info['weight']; ?>" required>
        </p>
        <p>
            <label>Poste préféré :</label>
            <select name="preferred_position" required>
                <option value="GB" <?php if($user_info['preferred_position'] == "GB") echo 'selected'; ?>>Gardien</option>
                <option value="DEF" <?php if($user_info['preferred_position'] == "DEF") echo 'selected'; ?>>Défenseur</option>
                <option value="LATERAL/PISTON" <?php if($user_info['preferred_position'] == "LATERAL/PISTON") echo 'selected'; ?>>Latéral/Piston</option>
                <option value="MILIEU DEF" <?php if($user_info['preferred_position'] == "MILIEU DEF") echo 'selected'; ?>>Milieu Défensif</option>
                <option value="MILIEU OFF" <?php if($user_info['preferred_position'] == "MILIEU OFF") echo 'selected'; ?>>Milieu Offensif</option>
                <option value="ATTAQUANT" <?php if($user_info['preferred_position'] == "ATTAQUANT") echo 'selected'; ?>>Attaquant</option>
            </select>
        </p>
        <p>
            <label>Surnom :</label>
            <input type="text" name="nickname" value="<?php echo $user_info['nickname']; ?>" required>
        </p>
        <p>
            <label>Photo de profil :</label>
            <input type="file" name="profile_pic" accept="image/*">
        </p>
        <p>
            <input type="submit" value="Mettre à jour">
        </p>
    </form>
</div>

</body>
</html>
