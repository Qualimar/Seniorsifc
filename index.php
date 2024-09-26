<?php
include('db.php');
/*include('functions.php');*/
include_once('jpgraph/src/jpgraph.php');
include_once('jpgraph/src/jpgraph_bar.php');
include_once('jpgraph/src/jpgraph_line.php');
if (!isset($_GET['m'])) {
	$menu = 'infos';
} else {
	$menu = $_GET['m'];
}
?>

<html>

<head>
	<title>SENIORS IFC</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css" type="text/css" title="style">
</head>

<body>
<?php
include('banner.php');
?>

<div class='maindiv'>
<?php
switch($menu) {
	case 'infos' :
		include('infos.php');
		break;
	case 'players' :
		include('players.php');
		break;
	case 'teams' :
		include('teams.php');
		break;
	case 'games' :
		include('games.php');
		break;
	case 'seasons' :
		include('seasons.php');
		break;
	case 'profiles' :
		include('profiles.php');
		break;
	case 'versus' :
		include('versus.php');
		break;
}

?>
</div>

<?php
include('bottom.php');
?>

<script type="text/javascript" src="functions.js"></script>

</body>

</html>
