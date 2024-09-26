<?php
if (!isset($_GET['id'])) {
    $id=1;
} else {
    $id=$_GET['id'];
}
$query = $db->query('SELECT * FROM players WHERE players_id='.$id);
$playerdata = $query->fetch();
$query = $db->query ('select
sum(goals) as goals,
sum(assists) as assists,
sum(red_card) as redcards,
sum(yellow_card) as yellowcards,
count(*) as played,
sum(starter) as starter,
sum(capitaine) as capitaine,
(select players_name from players where stats.players_id=players.players_id) as name,
(select players_firstname from players where stats.players_id=players.players_id) as firstname
from stats WHERE players_id='.$id.';');
$playerdata = $query->fetch();
?>

<div>
<form method='GET' action='index.php?m=profiles'>
<input type='hidden' name='m' value='profiles'>
<select name='id'>
<?php
$query = $db->query('SELECT players_id,players_name,players_firstname FROM players
ORDER BY players_firstname;');
while ($data = $query->fetch()) {
    echo '<option value="'.$data['players_id'].'"';
    if ($id==$data['players_id']) {
        echo ' selected';
    }
    echo '>'.$data['players_firstname'].' '.$data['players_name'].'</option>'.PHP_EOL;
}
?>
</select>
<input type='submit' value='Voir les stats'>
</form>
</div>

<h1><?php echo $playerdata['firstname'].' '.$playerdata['name']; ?></h1>

<h2>Stats globales</h2>

<table>
<thead>
<!--<tr><th>Nom</th><th>Joués</th><th>Titulaire</th><th>Buts</th><th>Pass D</th><th>Carton Jaune</th><th>Carton Rouge</th></tr>-->
</thead>
<tbody>
<?php
echo '<tr><td>Matchs joués</td><td>'.$playerdata['played'].'</td></tr>';
echo '<tr><td>Titulaires</td><td>'.$playerdata['starter'].'</td></tr>';
echo '<tr><td>Buts</td><td>'.$playerdata['goals'].'</td></tr>';
echo '<tr><td>Pass D</td><td>'.$playerdata['assists'].'</td></tr>';
echo '<tr><td>Carton Jaune</td><td>'.$playerdata['yellowcards'].'</td></tr>';
echo '<tr><td>Carton Rouge</td><td>'.$playerdata['redcards'].'</td></tr>';
echo '<tr><td>Capitaine</td><td>'.$playerdata['capitaine'].'</td></tr>';
?>
</tbody>
</table>