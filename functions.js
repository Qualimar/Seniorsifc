function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Cacher tous les contenus des onglets
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Démarquer tous les liens des onglets
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Afficher le contenu de l'onglet sélectionné
    document.getElementById(tabName).style.display = "block";

    // Marquer le lien de l'onglet sélectionné comme actif
    evt.currentTarget.classList.add("active");
}

// Par défaut, afficher le premier onglet
document.getElementById("tab1").style.display = "block";
document.getElementsByClassName("tablinks")[0].classList.add("active");

document.getElementById('menu').addEventListener('change', function() {
    var selectedPlayer = this.value;
    // Masquer toutes les divs de stats des joueurs
    var playerStatsDivs = document.getElementsByClassName('player-stats');
    for (var i = 0; i < playerStatsDivs.length; i++) {
      playerStatsDivs[i].style.display = 'none';
    }
    // Afficher la div de stats du joueur sélectionné
    document.getElementById(selectedPlayer).style.display = 'block';
});
