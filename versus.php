<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Inclure le fichier de configuration
require 'config.php';

// Fonction pour récupérer les statistiques d'un joueur
function get_player_stats($db, $player_id) {
    $query = $db->prepare("
        SELECT 
            p.players_firstname,
            p.players_name,
            COUNT(s.games_id) AS played,
            SUM(s.starter) AS starter,
            SUM(s.goals) AS goals,
            SUM(s.assists) AS assists,
            SUM(s.yellow_card) AS yellowcards,
            SUM(s.red_card) AS redcards,
            SUM(s.capitaine) AS capitaine
        FROM players p
        LEFT JOIN stats s ON p.players_id = s.players_id
        WHERE p.players_id = :player_id
        GROUP BY p.players_id
    ");
    $query->execute(['player_id' => $player_id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Si une requête AJAX est envoyée pour comparer deux joueurs
if (isset($_POST['player1']) && isset($_POST['player2'])) {
    $player1_id = (int)$_POST['player1'];
    $player2_id = (int)$_POST['player2'];

    // Récupérer les stats des deux joueurs
    $player1_stats = get_player_stats($db, $player1_id);
    $player2_stats = get_player_stats($db, $player2_id);

    // Comparaison des joueurs en tableau
    if ($player1_stats && $player2_stats) {
        echo '<table border="1" class="versus-table">
                <thead>
                    <tr>
                        <th>Statistique</th>
                        <th>' . $player1_stats['players_firstname'] . ' ' . $player1_stats['players_name'] . '</th>
                        <th>' . $player2_stats['players_firstname'] . ' ' . $player2_stats['players_name'] . '</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Matchs joués</td>
                        <td>' . $player1_stats['played'] . '</td>
                        <td>' . $player2_stats['played'] . '</td>
                    </tr>
                    <tr>
                        <td>Titulaires</td>
                        <td>' . bold_best($player1_stats['starter'], $player2_stats['starter']) . '</td>
                        <td>' . bold_best($player2_stats['starter'], $player1_stats['starter']) . '</td>
                    </tr>
                    <tr>
                        <td>Buts</td>
                        <td>' . bold_best($player1_stats['goals'], $player2_stats['goals']) . '</td>
                        <td>' . bold_best($player2_stats['goals'], $player1_stats['goals']) . '</td>
                    </tr>
                    <tr>
                        <td>Passes décisives</td>
                        <td>' . bold_best($player1_stats['assists'], $player2_stats['assists']) . '</td>
                        <td>' . bold_best($player2_stats['assists'], $player1_stats['assists']) . '</td>
                    </tr>
                    <tr>
                        <td>Cartons jaunes</td>
                        <td>' . bold_best($player2_stats['yellowcards'], $player1_stats['yellowcards']) . '</td>
                        <td>' . bold_best($player1_stats['yellowcards'], $player2_stats['yellowcards']) . '</td>
                    </tr>
                    <tr>
                        <td>Cartons rouges</td>
                        <td>' . bold_best($player2_stats['redcards'], $player1_stats['redcards']) . '</td>
                        <td>' . bold_best($player1_stats['redcards'], $player2_stats['redcards']) . '</td>
                    </tr>
                    <tr>
                        <td>Capitaine</td>
                        <td>' . bold_best($player1_stats['capitaine'], $player2_stats['capitaine']) . '</td>
                        <td>' . bold_best($player2_stats['capitaine'], $player1_stats['capitaine']) . '</td>
                    </tr>
                </tbody>
            </table>';
    } else {
        echo '<p>Erreur : Un ou plusieurs joueurs n\'ont pas été trouvés.</p>';
    }
    exit();
}

// Comparer deux statistiques et mettre en gras la meilleure
function bold_best($stat1, $stat2) {
    if ($stat1 > $stat2) {
        return '<strong>' . $stat1 . '</strong>';
    } elseif ($stat2 > $stat1) {
        return $stat1;
    } else {
        return $stat1; // Aucun changement si égalité
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparaison des joueurs</title>
    <link rel="stylesheet" href="style.css"> <!-- Garder le style existant -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h1>Comparer deux joueurs</h1>

<!-- Formulaire de sélection des joueurs -->
<form id="versus-form">
    <label for="player1">Joueur 1 :</label>
    <select name="player1" id="player1">
        <?php
        $players = $db->query("SELECT players_id, players_firstname, players_name FROM players");
        while ($player = $players->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $player['players_id'] . '">' . $player['players_firstname'] . ' ' . $player['players_name'] . '</option>';
        }
        ?>
    </select>
    <br><br>
    <label for="player2">Joueur 2 :</label>
    <select name="player2" id="player2">
        <?php
        $players = $db->query("SELECT players_id, players_firstname, players_name FROM players");
        while ($player = $players->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $player['players_id'] . '">' . $player['players_firstname'] . ' ' . $player['players_name'] . '</option>';
        }
        ?>
    </select>
    <br><br>
    <input type="submit" value="Comparer">
</form>

<!-- Zone pour afficher le résultat de la comparaison -->
<div id="comparison-result"></div>

<script>
    // Fonction pour envoyer la requête via AJAX et afficher les résultats
    $(document).ready(function() {
        $('#versus-form').on('submit', function(event) {
            event.preventDefault();
            var player1 = $('#player1').val();
            var player2 = $('#player2').val();

            $.ajax({
                type: 'POST',
                url: 'versus.php',
                data: { player1: player1, player2: player2 },
                success: function(response) {
                    $('#comparison-result').html(response);
                }
            });
        });
    });
</script>

</body>
</html>
