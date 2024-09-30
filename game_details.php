<?php
require 'config.php';

// Récupérer l'ID du match depuis l'URL
if (isset($_GET['id'])) {
    $game_id = (int)$_GET['id'];

    // Récupérer les détails du match
    $game_query = $db->prepare("
        SELECT g.date_match, t1.teams_name AS equipe1, t2.teams_name AS equipe2, g.score_1, g.score_2
        FROM games g
        JOIN teams t1 ON g.id_equipe_1 = t1.teams_id
        JOIN teams t2 ON g.id_equipe_2 = t2.teams_id
        WHERE g.games_id = :game_id
    ");
    $game_query->execute(['game_id' => $game_id]);
    $game = $game_query->fetch(PDO::FETCH_ASSOC);

    if ($game) {
        ?>

        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Détails du match</title>
            <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
        </head>
        <body>

        <h1>Détails du match</h1>
        <p><strong>Date :</strong> <?php echo $game['date_match']; ?></p>
        <p><strong>Score :</strong> <?php echo $game['equipe1'] . ' ' . $game['score_1'] . ' - ' . $game['score_2'] . ' ' . $game['equipe2']; ?></p>

        <h2>Composition et Détails</h2>

        <!-- Table pour afficher les joueurs titulaires et remplaçants -->
        <table border="1">
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Poste</th>
                    <th>Buts</th>
                    <th>Passes D.</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Récupérer les statistiques des joueurs pour ce match
            $stats_query = $db->prepare("
                SELECT p.players_firstname, p.players_name, s.goals, s.assists, s.starter, s.postes
                FROM stats s
                JOIN players p ON s.players_id = p.players_id
                WHERE s.games_id = :game_id
                ORDER BY s.starter DESC, 
                         FIELD(s.postes, 'GARDIEN', 'DEF', 'MIL', 'ATT')
            ");
            $stats_query->execute(['game_id' => $game_id]);

            // Affichage des titulaires
            echo '<tr><td colspan="4"><strong>Titulaires</strong></td></tr>';
            while ($player = $stats_query->fetch(PDO::FETCH_ASSOC)) {
                if ($player['starter']) { // Afficher les titulaires
                    echo '<tr>
                        <td>' . $player['players_firstname'] . ' ' . $player['players_name'] . '</td>
                        <td>' . $player['postes'] . '</td>
                        <td>' . $player['goals'] . '</td>
                        <td>' . $player['assists'] . '</td>
                    </tr>';
                }
            }

            // Remettre le curseur au début pour les remplaçants
            $stats_query->execute(['game_id' => $game_id]);

            // Affichage des remplaçants
            echo '<tr><td colspan="4"><strong>Remplaçants</strong></td></tr>';
            while ($player = $stats_query->fetch(PDO::FETCH_ASSOC)) {
                if (!$player['starter']) { // Afficher les remplaçants
                    echo '<tr>
                        <td>' . $player['players_firstname'] . ' ' . $player['players_name'] . '</td>
                        <td>' . $player['postes'] . '</td>
                        <td>' . $player['goals'] . '</td>
                        <td>' . $player['assists'] . '</td>
                    </tr>';
                }
            }
            ?>
            </tbody>
        </table>

        <!-- Bouton pour revenir à l'accueil -->
        <p><a href="index.php" class="nav-link">Retour à l'accueil</a></p>

        </body>
        </html>

        <?php
    } else {
        echo "<p>Match non trouvé.</p>";
    }
} else {
    echo "<p>ID de match manquant.</p>";
}
?>
