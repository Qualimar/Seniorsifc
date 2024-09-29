<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Connexion à la base de données
$host = 'localhost';
$dbname = 'ifc';
$username = 'root';
$password = 'Aliceromy1110*';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Liste des équipes avec l'ordre correct (ISNEAUVILLE FC APREM, ISNEAUVILLE FC D1, ISNEAUVILLE FC D3)
$team_order = [
    3 => 'ISNEAUVILLE FC APREM',
    1 => 'ISNEAUVILLE FC D1',
    2 => 'ISNEAUVILLE FC D3'
];

// Fonction pour afficher les statistiques des équipes dans l'ordre
function display_team_stats($db, $team_order, $type) {
    $title = "";

    if ($type == 'overall') {
        $title = "Statistiques Globales";
        $condition_total_matchs = "(g.id_equipe_1 = t.teams_id OR g.id_equipe_2 = t.teams_id)";
        $condition_buts_marques = "SUM(CASE WHEN g.id_equipe_1 = t.teams_id THEN g.score_1 WHEN g.id_equipe_2 = t.teams_id THEN g.score_2 ELSE 0 END)";
        $condition_buts_encaisse = "SUM(CASE WHEN g.id_equipe_1 = t.teams_id THEN g.score_2 WHEN g.id_equipe_2 = t.teams_id THEN g.score_1 ELSE 0 END)";
    } elseif ($type == 'home') {
        $title = "Statistiques à Domicile";
        $condition_total_matchs = "g.id_equipe_1 = t.teams_id";
        $condition_buts_marques = "SUM(CASE WHEN g.id_equipe_1 = t.teams_id THEN g.score_1 ELSE 0 END)";
        $condition_buts_encaisse = "SUM(CASE WHEN g.id_equipe_1 = t.teams_id THEN g.score_2 ELSE 0 END)";
    } elseif ($type == 'away') {
        $title = "Statistiques à l'Extérieur";
        $condition_total_matchs = "g.id_equipe_2 = t.teams_id";
        $condition_buts_marques = "SUM(CASE WHEN g.id_equipe_2 = t.teams_id THEN g.score_2 ELSE 0 END)";
        $condition_buts_encaisse = "SUM(CASE WHEN g.id_equipe_2 = t.teams_id THEN g.score_1 ELSE 0 END)";
    }

    echo "<h2>$title</h2>";
    echo '<table border="1">
            <thead>
                <tr>
                    <th>Nom de l\'équipe</th>
                    <th>Total de matchs joués</th>
                    <th>Total de buts marqués</th>
                    <th>Total de buts encaissés</th>
                    <th>Différence de buts</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($team_order as $team_id => $team_name) {
        // Requête SQL pour récupérer les statistiques
        $query = $db->prepare("
            SELECT 
                t.teams_name,
                COUNT(DISTINCT g.games_id) AS total_matchs,
                $condition_buts_marques AS total_buts_marques,
                $condition_buts_encaisse AS total_buts_encaisse,
                ($condition_buts_marques - $condition_buts_encaisse) AS difference_buts
            FROM teams t
            LEFT JOIN games g ON (g.id_equipe_1 = t.teams_id OR g.id_equipe_2 = t.teams_id)
            WHERE t.teams_id = :team_id
            AND g.score_1 IS NOT NULL AND g.score_2 IS NOT NULL
            AND $condition_total_matchs
            GROUP BY t.teams_id
        ");
        $query->execute(['team_id' => $team_id]);

        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $data['teams_name'] . '</td>';
            echo '<td>' . $data['total_matchs'] . '</td>';
            echo '<td>' . $data['total_buts_marques'] . '</td>';
            echo '<td>' . $data['total_buts_encaisse'] . '</td>';
            echo '<td>' . $data['difference_buts'] . '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody></table>';
}

// Affichage des trois tableaux pour overall, domicile, et extérieur
echo "<h1>Statistiques des équipes ISNEAUVILLE</h1>";

display_team_stats($db, $team_order, 'overall');
display_team_stats($db, $team_order, 'home');
display_team_stats($db, $team_order, 'away');
?>
