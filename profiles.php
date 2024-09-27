<?php
if (!isset($_GET['id'])) {
    $id = 1;
} else {
    $id = $_GET['id'];
}

// Récupération des données du joueur
$query = $db->query('SELECT * FROM players WHERE players_id=' . $id);
$player_info = $query->fetch();

// Récupération des statistiques du joueur
$query = $db->query('SELECT
    sum(goals) as goals,
    sum(assists) as assists,
    sum(red_card) as redcards,
    sum(yellow_card) as yellowcards,
    count(*) as played,
    sum(starter) as starter,
    sum(capitaine) as capitaine,
    (SELECT players_name FROM players WHERE stats.players_id = players.players_id) as name,
    (SELECT players_firstname FROM players WHERE stats.players_id = players.players_id) as firstname
    FROM stats WHERE players_id=' . $id . ';');
$player_stats = $query->fetch();
?>

<div>
    <form method='GET' action='index.php?m=profiles'>
        <input type='hidden' name='m' value='profiles'>
        <select name='id'>
            <?php
            $query = $db->query('SELECT players_id,players_name,players_firstname FROM players ORDER BY players_firstname;');
            while ($data = $query->fetch()) {
                echo '<option value="' . $data['players_id'] . '"';
                if ($id == $data['players_id']) {
                    echo ' selected';
                }
                echo '>' . $data['players_firstname'] . ' ' . $data['players_name'] . '</option>' . PHP_EOL;
            }
            ?>
        </select>
        <input type='submit' value='Voir les stats'>
    </form>
</div>

<div class="container"> <!-- Conteneur principal qui regroupe le profil et les stats -->
    <!-- Section profil du joueur -->
    <div class="profile-section">
        <h1><?php echo $player_info['players_firstname'] . ' ' . $player_info['players_name']; ?></h1>
        <img src="<?php echo !empty($player_info['profile_pic']) ? $player_info['profile_pic'] : 'default_profile_pic.jpg'; ?>" alt="Photo de profil" class="profile-pic" style="max-width: 150px;">
        <p><strong>Âge :</strong> <?php echo $player_info['age']; ?> ans</p>
        <p><strong>Taille :</strong> <?php echo $player_info['height']; ?> cm</p>
        <p><strong>Poids :</strong> <?php echo $player_info['weight']; ?> kg</p>
        <p><strong>Poste préféré :</strong> <?php echo $player_info['preferred_position']; ?></p>
        <p><strong>Surnom :</strong> <?php echo $player_info['nickname']; ?></p>
    </div>

    <!-- Section statistiques du joueur -->
    <div class="stats-section">
        <h2>Stats globales</h2>
        <table border="1">
            <tbody>
                <?php
                echo '<tr><td>Matchs joués</td><td>' . $player_stats['played'] . '</td></tr>';
                echo '<tr><td>Titulaires</td><td>' . $player_stats['starter'] . '</td></tr>';
                echo '<tr><td>Buts</td><td>' . $player_stats['goals'] . '</td></tr>';
                echo '<tr><td>Pass D</td><td>' . $player_stats['assists'] . '</td></tr>';
                echo '<tr><td>Carton Jaune</td><td>' . $player_stats['yellowcards'] . '</td></tr>';
                echo '<tr><td>Carton Rouge</td><td>' . $player_stats['redcards'] . '</td></tr>';
                echo '<tr><td>Capitaine</td><td>' . $player_stats['capitaine'] . '</td></tr>';
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Conteneur principal pour séparer le profil et les stats */
.container {
    display: flex;
    justify-content: space-between;
    padding: 20px;
}

/* Section profil du joueur */
.profile-section {
    flex: 1;
    padding: 10px;
    background-color: #1D1D1D;
    color: white;
    text-align: center;
}

/* Image de profil */
.profile-pic {
    border-radius: 50%;
    max-width: 100px;
    margin-bottom: 10px;
}

/* Section statistiques du joueur */
.stats-section {
    flex: 1;
    padding: 10px;
    background-color: #252525;
    color: white;
}
</style>
