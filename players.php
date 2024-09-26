<h1>Statistiques des équipes ISNEAUVILLE</h1>

<?php
// Connexion à la base de données
require 'config.php';

// Liste des équipes à afficher
$teams = [
    3 => 'ISNEAUVILLE FC',
    1 => 'ISNEAUVILLE FC 2',
    2 => 'ISNEAUVILLE FC 3'
];

// Fonction pour afficher les statistiques des joueurs d'une équipe
function display_team_stats($db, $team_id, $team_name) {
    echo "<h2>$team_name</h2>";
    echo '
    <table>
        <thead>
            <tr><th>Nom</th><th>Joués</th><th>Titulaire</th><th>Buts</th><th>Pass D</th><th>Carton Jaune</th><th>Carton Rouge</th><th>Capitaine</th></tr>
        </thead>
        <tbody>';

    // Préparer et exécuter la requête pour récupérer les statistiques des joueurs de l'équipe
    $query = $db->prepare('
        SELECT
            SUM(goals) AS goals,
            SUM(assists) AS assists,
            SUM(red_card) AS redcards,
            SUM(yellow_card) AS yellowcards,
            COUNT(*) AS played,
            SUM(starter) AS starter,
            SUM(capitaine) AS capitaine,
            (SELECT players_name FROM players WHERE stats.players_id = players.players_id) AS name,
            (SELECT players_firstname FROM players WHERE stats.players_id = players.players_id) AS firstname
        FROM stats
        WHERE teams_id = ?
        GROUP BY players_id
    ');
    
    $query->execute([$team_id]);

    // Affichage des données
    while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr><td>' . $data['firstname'] . ' ' . $data['name'] .
        '</td><td>' . $data['played'] . '</td><td>' . $data['starter'] .
        '</td><td>' . $data['goals'] . '</td><td>' . $data['assists'] .
        '</td><td>' . $data['yellowcards'] . '</td><td>' . $data['redcards'] .
        '</td><td>' . $data['capitaine'] . '</td></tr>' . PHP_EOL;
    }

    echo '</tbody></table>';
}

// Affichage des statistiques pour chaque équipe
foreach ($teams as $team_id => $team_name) {
    display_team_stats($db, $team_id, $team_name);
}
?>
