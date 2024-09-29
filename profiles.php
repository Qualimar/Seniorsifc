<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    $id = 1;
} else {
    $id = $_GET['id'];
}

// Récupérer les informations du joueur et l'utilisateur lié pour l'image de profil
$query = $db->query('SELECT p.*, u.profile_pic FROM players p LEFT JOIN users u ON p.players_id = u.players_id WHERE p.players_id = ' . $id);
$playerdata = $query->fetch();

// Récupérer les statistiques du joueur
$query = $db->query('
    SELECT
        sum(goals) as goals,
        sum(assists) as assists,
        sum(red_card) as redcards,
        sum(yellow_card) as yellowcards,
        count(*) as played,
        sum(starter) as starter,
        sum(capitaine) as capitaine
    FROM stats WHERE players_id = ' . $id
);
$stats = $query->fetch();
?>

<!-- Formulaire pour sélectionner un autre joueur -->
<div>
    <form method='GET' action='index.php?m=profiles'>
        <input type='hidden' name='m' value='profiles'>
        <select name='id'>
            <?php
            $query = $db->query('SELECT players_id, players_name, players_firstname FROM players ORDER BY players_firstname;');
            while ($data = $query->fetch()) {
                echo '<option value="' . $data['players_id'] . '"';
                if ($id == $data['players_id']) {
                    echo ' selected';
                }
                echo '>' . $data['players_firstname'] . ' ' . $data['players_name'] . '</option>';
            }
            ?>
        </select>
        <input type='submit' value='Voir les stats'>
    </form>
</div>

<!-- Affichage du profil joueur -->
<div style="display: flex;">
    <!-- Partie gauche : photo et informations du joueur -->
    <div style="flex: 1; text-align: center;">
        <h1><?php echo $playerdata['players_firstname'] . ' ' . $playerdata['players_name']; ?></h1>
        
        <?php if (!empty($playerdata['profile_pic'])): ?>
            <img src="<?php echo $playerdata['profile_pic']; ?>" alt="Photo de profil" style="width: 150px; height: 150px; border-radius: 50%;">
        <?php else: ?>
            <img src="default-profile.png" alt="Photo de profil" style="width: 150px; height: 150px; border-radius: 50%;">
        <?php endif; ?>

        <p><strong>Âge :</strong> <?php echo $playerdata['age']; ?></p>
        <p><strong>Taille :</strong> <?php echo $playerdata['height']; ?> cm</p>
        <p><strong>Poids :</strong> <?php echo $playerdata['weight']; ?> kg</p>
        <p><strong>Poste préféré :</strong> <?php echo $playerdata['preferred_position']; ?></p>
        <p><strong>Surnom :</strong> <?php echo $playerdata['nickname']; ?></p>
    </div>

    <!-- Partie droite : statistiques -->
    <div style="flex: 1;">
        <h2>Stats globales</h2>
        <table>
            <tbody>
                <tr><td>Matchs joués</td><td><?php echo $stats['played']; ?></td></tr>
                <tr><td>Titulaires</td><td><?php echo $stats['starter']; ?></td></tr>
                <tr><td>Buts</td><td><?php echo $stats['goals']; ?></td></tr>
                <tr><td>Pass D</td><td><?php echo $stats['assists']; ?></td></tr>
                <tr><td>Carton Jaune</td><td><?php echo $stats['yellowcards']; ?></td></tr>
                <tr><td>Carton Rouge</td><td><?php echo $stats['redcards']; ?></td></tr>
                <tr><td>Capitaine</td><td><?php echo $stats['capitaine']; ?></td></tr>
            </tbody>
        </table>
    </div>
</div>
