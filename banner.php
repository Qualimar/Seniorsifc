<div class='banner'>
Stats SENIORS IFC
</div>
<table class='tablelinks'>
<tr>
<?php
if ($menu=='infos') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=infos">Infos</a></th>
<?php
if ($menu=='players') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=players">Joueurs</a></th>
<?php
if ($menu=='teams') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=teams">Equipes</a></th>
<?php
if ($menu=='games') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=games">Matchs</a></th>
<?php
if ($menu=='maps') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=profiles">Profils</a></th>
<?php
if ($menu=='versus') {
    echo '<th class="selectedth">';
} else {
    echo '<th>';
}
?>
<a href="index.php?m=versus">Versus</a></th>
</tr>
</table>
