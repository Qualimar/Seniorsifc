<h1>Statistiques des joueurs ISNEAUVILLE</h1>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Connexion à la base de données
require 'config.php';

// Requête pour récupérer les statistiques globales des joueurs sans distinction par équipe
// Triés par nombre de matchs joués (played) de manière décroissante et, en cas d'égalité, par le nombre de fois titulaire (starter)
$query = $db->query('
    SELECT
        p.players_firstname,
        p.players_name,
        SUM(s.goals) AS goals,
        SUM(s.assists) AS assists,
        SUM(s.red_card) AS redcards,
        SUM(s.yellow_card) AS yellowcards,
        COUNT(*) AS played,
        SUM(s.starter) AS starter,
        SUM(s.capitaine) AS capitaine
    FROM players p
    LEFT JOIN stats s ON p.players_id = s.players_id
    WHERE s.teams_id IN (1, 2, 3)
    GROUP BY p.players_id
    ORDER BY played DESC, starter DESC;
');

$players = $query->fetchAll();
?>

<h2>Statistiques des joueurs</h2>

<!-- Tableau unique avec les statistiques globales des joueurs triés par nombre de matchs joués (décroissant), puis par nombre de fois titulaire en cas d'égalité -->
<table border="1">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Joués</th>
            <th>Titulaire</th>
            <th>Buts</th>
            <th>Pass D</th>
            <th>Carton Jaune</th>
            <th>Carton Rouge</th>
            <th>Capitaine</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($players as $player): ?>
            <tr>
                <td><?php echo $player['players_firstname'] . ' ' . $player['players_name']; ?></td>
                <td><?php echo $player['played']; ?></td>
                <td><?php echo $player['starter']; ?></td>
                <td><?php echo $player['goals']; ?></td>
                <td><?php echo $player['assists']; ?></td>
                <td><?php echo $player['yellowcards']; ?></td>
                <td><?php echo $player['redcards']; ?></td>
                <td><?php echo $player['capitaine']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
