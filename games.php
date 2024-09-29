<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Inclure le fichier de configuration
require 'config.php';

// Liste des équipes à afficher dans l'ordre souhaité
$team_ids = [
    3 => 'ISNEAUVILLE FC APREM',
    1 => 'ISNEAUVILLE FC D1',
    2 => 'ISNEAUVILLE FC D3'
];

// Fonction pour afficher la dynamique des 5 derniers matchs et les détails des matchs
function display_last_games($db, $team_ids) {
    foreach ($team_ids as $team_id => $team_name) {
        // Requête pour récupérer les 5 derniers matchs joués avec un score
        $query = $db->prepare("
            SELECT g.games_id, g.date_match, g.id_equipe_1, g.id_equipe_2, g.score_1, g.score_2, 
                   t1.teams_name AS equipe1, t2.teams_name AS equipe2
            FROM games g
            JOIN teams t1 ON g.id_equipe_1 = t1.teams_id
            JOIN teams t2 ON g.id_equipe_2 = t2.teams_id
            WHERE (g.id_equipe_1 = :team_id OR g.id_equipe_2 = :team_id)
            AND g.score_1 IS NOT NULL AND g.score_2 IS NOT NULL
            ORDER BY g.date_match DESC
            LIMIT 5
        ");
        $query->execute(['team_id' => $team_id]);

        // Affichage du nom de l'équipe
        echo "<h2>Derniers matchs pour $team_name</h2>";

        // Affichage de la dynamique des résultats dans un tableau avec une seule ligne et 5 cellules
        echo '<table border="1" style="margin-bottom: 10px;">
                <tr>';
        
        $results = []; // Tableau pour stocker la dynamique des résultats
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            // Calculer la dynamique (victoire, nul, défaite)
            if (($data['id_equipe_1'] == $team_id && $data['score_1'] > $data['score_2']) ||
                ($data['id_equipe_2'] == $team_id && $data['score_2'] > $data['score_1'])) {
                // Victoire
                $results[] = '<td style="text-align:center;"><div style="color:white; background-color:green; border-radius:50%; width:20px; height:20px; line-height:20px; text-align:center; display:inline-block;">✔</div></td>';
            } elseif ($data['score_1'] == $data['score_2']) {
                // Match nul
                $results[] = '<td style="text-align:center;"><div style="color:white; background-color:gray; border-radius:50%; width:20px; height:20px; line-height:20px; text-align:center; display:inline-block;">−</div></td>';
            } else {
                // Défaite
                $results[] = '<td style="text-align:center;"><div style="color:white; background-color:red; border-radius:50%; width:20px; height:20px; line-height:20px; text-align:center; display:inline-block;">✘</div></td>';
            }
        }

        // Ajouter les résultats au tableau et compléter si moins de 5 matchs
        for ($i = 0; $i < 5; $i++) {
            echo isset($results[$i]) ? $results[$i] : '<td></td>'; // Laisser vide si moins de 5 matchs
        }

        echo '</tr></table>';

        // Réinitialiser le pointeur de la requête pour réafficher les 5 derniers matchs
        $query->execute(['team_id' => $team_id]);

        // Affichage des 5 derniers matchs
        echo '<table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Domicile</th>
                        <th>Score</th>
                        <th>Exterieur</th>
                    </tr>
                </thead>
                <tbody>';
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            // Afficher les détails des matchs
            echo '<tr>';
            echo '<td>' . $data['date_match'] . '</td>';
            echo '<td>' . $data['equipe1'] . '</td>';
            echo '<td>' . $data['score_1'] . ' - ' . $data['score_2'] . '</td>';
            echo '<td>' . $data['equipe2'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';

        // Requête pour récupérer le prochain match
        $next_match_query = $db->prepare("
            SELECT g.games_id, g.date_match, g.id_equipe_1, g.id_equipe_2, 
                   t1.teams_name AS equipe1, t2.teams_name AS equipe2
            FROM games g
            JOIN teams t1 ON g.id_equipe_1 = t1.teams_id
            JOIN teams t2 ON g.id_equipe_2 = t2.teams_id
            WHERE (g.id_equipe_1 = :team_id OR g.id_equipe_2 = :team_id)
            AND g.score_1 IS NULL AND g.score_2 IS NULL
            ORDER BY g.date_match ASC
            LIMIT 1
        ");
        $next_match_query->execute(['team_id' => $team_id]);
        $next_match = $next_match_query->fetch(PDO::FETCH_ASSOC);

        // Affichage du prochain match
        if ($next_match) {
            echo "<h2>Prochain match pour $team_name</h2>";
            echo '<table border="1">
                    <tr>
                        <th>Date</th>
                        <th>Domicile</th>
                        <th>Exterieur</th>
                    </tr>';
            echo '<tr>';
            echo '<td>' . $next_match['date_match'] . '</td>';
            echo '<td>' . $next_match['equipe1'] . '</td>';
            echo '<td>' . $next_match['equipe2'] . '</td>';
            echo '</tr></table>';
        } else {
            echo "<h2>Aucun match à venir pour $team_name.</h2>";
        }
    }
}

// Affichage des 5 derniers matchs pour chaque équipe dans l'ordre souhaité
echo "<h1>5 Derniers Matchs Joués et Prochains Matchs</h1>";
display_last_games($db, $team_ids);
?>
