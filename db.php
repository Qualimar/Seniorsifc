<?php
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');

$dbhost='localhost';
$dbname='ifc';
$dbuser='ifc';
$dbpass='Aliceromy1110*';

try {
	$db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass, 
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(Exception $e) {
	die('Erreur : '.$e->getMessage());
}
?>
