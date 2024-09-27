<?php
// Inclure la configuration et démarrer la session
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$query = $db->prepare("
    SELECT u.username, u.email, p.players_firstname, p.players_name, p.age, p.height, p.weight, p.preferred_position, p.nickname 
    FROM users u 
    LEFT JOIN players p ON u.players_id = p.players_id 
    WHERE u.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);
$user_info = $query->fetch(PDO::FETCH_ASSOC);

if (!$user_info) {
    echo "Erreur lors de la récupération des informations.";
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

<div class="maindiv">
    <h1>Mon Profil</h1>
    <div class="statdiv">
        <h2><?php echo $user_info['players_firstname'] . ' ' . $user_info['players_name']; ?></h2>
        <p><span class="stattext">Email :</span> <?php echo $user_info['email']; ?></p>
        <p><span class="stattext">Âge :</span> <?php echo $user_info['age']; ?> ans</p>
        <p><span class="stattext">Taille :</span> <?php echo $user_info['height']; ?> cm</p>
        <p><span class="stattext">Poids :</span> <?php echo $user_info['weight']; ?> kg</p>
        <p><span class="stattext">Poste préféré :</span> <?php echo $user_info['preferred_position']; ?></p>
        <p><span class="stattext">Surnom :</span> <?php echo $user_info['nickname']; ?></p>
    </div>
</div>

</body>
</html>
