var requestURL = 'data/adresses.json'; // URL du fichier json à charger, mis dans une variable
var request = new XMLHttpRequest(); // Création d'un nouvel objet XMLHttpRequest avec le mot clé "new"

request.open('GET', requestURL); // Avec la méthode HTTP GET, on crée une requête afin de récupérer les données situé à l'adresse requestURL
request.responseType = 'json'; // On indique le type de réponse attendue
request.send(); // Puis on envoie la requête

var cardsData

request.onload = function () {
  cardsData = request.response;
  var paramAchat = JSON.parse(localStorage.getItem('paramAchat'));

  if (paramAchat != null) {
    function filtrerParRecherche(liste, critere) {
      // .filter() permet de garder les éléments passant le test passé en paramêtre
      return liste.filter(
        candidat => candidat.titre === critere.adresse
      );
    }

    var maisonSelectionnee = filtrerParRecherche(cardsData, paramAchat);
    créerExpo(maisonSelectionnee);

    console.log(maisonSelectionnee);
    let imageMaison = maisonSelectionnee.map(a => a.img);

    document.getElementById("achat-fond-image").style["background-image"] = "url(" + imageMaison + ")";
    document.getElementById("achat-image").setAttribute("src", imageMaison);

    function créerExpo(maisonSelectionnee) {
      new Vue({
        el: '#app',
        data: {
          cards: maisonSelectionnee,
        },
        filters: {
          versMonnaie: function (valeur) {
            if (typeof valeur !== "number") {
              return valeur;
            }
            var format = new Intl.NumberFormat('fr-FR', {
              style: 'currency',
              currency: 'EUR',
              minimumFractionDigits: 0
            });
            return format.format(valeur);
          }
        },
        methods: {
          redirectionCarte: function (event) {
            const clicExpoMaison = {
              "adresse": $(event.currentTarget).parent().find("h3").text() || null,
            }
            localStorage.setItem('clicExpoMaison', JSON.stringify(clicExpoMaison));
            window.location.href = "immobilier/carte"; // Direction la carte
          },
          type: function () {
            if (cardsData[0].commerce === "1") {
              return "Commerce";
            } else if (cardsData[0].appartement === "1") {
              return "Appartement";
            } else if (cardsData[0].maison === "1") {
              return "Maison";
            } else if (cardsData[0].industrie === "1") {
              return "Industrie";
            } else {
              return "Inconnu";
            }
          },
          statut: function () {
            if (cardsData[0].ouvert === "1") {
              return "Libre";
            } else if (cardsData[0].ouvert === "0") {
              return 'Occupé';
            } else {
              return "Inconnu";
            }
          }
        }
      });
    }

  } else {
    window.location.href = "index.php"; // Direction l'accueil
  }
}