<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Inclure le fichier de configuration
require 'config.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations du Club</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclure votre fichier CSS -->
</head>
<body>

<div class="teamsdiv">
    <h1>ISNEAUVILLE FC - Résultat du week-end</h1>

    <!-- Section des actualités -->
    <div class="statdiv">
    <h2>Résultat du 29/09</h2>
    <p class="greytext">
        Cette section affiche les derniers résultats des 3 équipes Seniors (Buteurs, passeurs et le MVP désigné par les Coachs)
    </p>
    <ul>
        <li><span class="stattext">29 Septembre 2024 :</span> Coupe Balluet - Défaite 3-2 sur le terrain de St Pierre de Varengeville. Buteurs : Edouard et Jeremy  MVP: Edouard </li>
    </ul>
</div>

<div class="maindiv">
    <h1>ISNEAUVILLE FC - Infos et Nouveautés</h1>

    <!-- Section des actualités -->
    <div class="statdiv">
    <h2>APERO</h2>
    <p class="greytext">
        Parce qu'après l'effort vient le réconfort, cette section est dédiée à l'événement le plus attendu de la semaine... L'APÉRO post-entraînement ! Un moment où la seule pression que l'on a , c'est celle que l'on boit !
    </p>
    <ul>
        <li><span class="stattext">26 Septembre 2024 :</span> Cette semaine l'apéro vous est offert par ...... alias ......</li>
    </ul>
</div>

    <div class="statdiv">
        <h2>Actualités du Club</h2>
        <p class="greytext">
            Bienvenue sur la page des actualités de l'ISNEAUVILLE FC. Ici, vous trouverez les dernières informations concernant le club, les événements à venir, et toutes les nouveautés concernant nos équipes.
        </p>
        <ul>
            <li><span class="stattext">24 Septembre 2024 :</span> Faute de présence suffisante, l'entrainement du jour est ANNULE !</li>
            <li><span class="stattext">01 Septembre 2024 :</span> Cette année, 3 équipes sont engagées. Pensez à faire valider vos licences .</li>
            <li><span class="stattext">13 Août 2024 :</span> Reprise de l'entrainement pour le groupe Seniors.</li>
        </ul>
    </div>

    <!-- Section des événements à venir -->
    <div class="statdiv">
        <h2>Événements à Venir</h2>
        <p class="greytext">Voici les prochains événements importants à noter dans vos agendas :</p>
        <ul>
            <li><span class="stattext">25 Octobre 2024 :</span> Exemple.... </li>
            <li><span class="stattext">12 Novembre 2024 :</span> Exemple..... </li>
            <li><span class="stattext">05 Décembre 2024 :</span> Exemple..... </li>
        </ul>
    </div>

    <!-- Section d'informations générales sur le club -->
    <div class="statdiv">
        <h2>Informations Générales</h2>
        <p class="greytext">Adresse du club :</p>
        <p><span class="stattext">Centre Sportif du Cheval Rouge, 76230 Isneauville</span></p>

        <p class="greytext">Contact :</p>
        <p><span class="stattext">Téléphone :</span> 02.35.59.07.03</p>
        <p><span class="stattext">Contact :</span> contact@isneauvillefc.fr</p>

        <p class="greytext">Coachs :</p>
        <p><span class="stattext"> Yvain Aprem D4:</span> 06.59.84.57.20</p>
        <p><span class="stattext"> Titi Matin D1 :</span> 07.89.21.60.87</p>
        <p><span class="stattext"> Nicolas Matin D3 :</span> 06.64.69.44.65</p>
    </div>

</div>

</body>
</html>
