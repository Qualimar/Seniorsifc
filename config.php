<?php
// Informations de connexion à la base de données
$host = 'localhost';
$dbname = 'ifc';
$username = 'root';
$password = 'Aliceromy1110*';

try {
    // Connexion à la base de données avec PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>
