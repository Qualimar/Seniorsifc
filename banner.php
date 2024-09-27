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
    if ($menu=='profiles') {
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

    <?php
    if ($menu=='my_profile') {
        echo '<th class="selectedth">';
    } else {
        echo '<th>';
    }
    ?>
    <a href="index.php?m=my_profile">Mon Profil</a></th>

    <?php
    if ($menu=='logout') {
        echo '<th class="selectedth">';
    } else {
        echo '<th>';
    }
    ?>
    <a href="index.php?m=logout">Deconnexion</a></th>
   </tr>
</table>
