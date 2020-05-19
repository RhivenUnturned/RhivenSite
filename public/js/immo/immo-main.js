//  |*************************|
//  |* LES CARTES DE MAISONS *|
//  |*************************|

var requestURL = 'data/adresses.json'; // URL du fichier json à charger, mis dans une variable
var request = new XMLHttpRequest(); // Création d'un nouvel objet XMLHttpRequest avec le mot clé "new"

request.open('GET', requestURL); // Avec la méthode HTTP GET, on crée une requête afin de récupérer les données situé à l'adresse requestURL
request.responseType = 'json'; // On indique le type de réponse attendue
request.send(); // Puis on envoie la requête

request.onload = function () {
  var cardsData = request.response; // On stocke nos données dans la constante "adresses"
  cardsData = shuffle(cardsData);
  créerExpo(cardsData);
}

function créerExpo(cardsData) {
  new Vue({
    el: '#app',
    data: {
      cards: cardsData,
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
        window.location.href = "carte.php"; // Direction la carte
      },
      redirectionAchat: function (event) {
        const paramAchat = {
          "adresse": $(event.currentTarget).parent().parent().find("h3").text() || null,
        }
        localStorage.setItem('paramAchat', JSON.stringify(paramAchat));
        window.location.href = "achat.php"; // Direction la carte
      },
      type: function (e) {
        if (cardsData[e].commerce === "1") {
          return "Commerce";
        } else if (cardsData[e].appartement === "1") {
          return "Appartement";
        } else if (cardsData[e].maison === "1") {
          return "Maison";
        } else if (cardsData[e].industrie === "1") {
          return "Industrie";
        } else {
          return "Inconnu";
        }
      },
      statut: function (e) {
        if (cardsData[e].ouvert === "1") {
          return "Libre";
        } else if (cardsData[e].ouvert === "0") {
          return 'Occupé';
        } else {
          return "Inconnu";
        }
      }
    }
  });
}

function shuffle(arr) {
  var temp, j, i = arr.length;
  while (--i) {
    j = ~~(Math.random() * (i + 1));
    temp = arr[i];
    arr[i] = arr[j];
    arr[j] = temp;
  }

  return arr;
}

//  |**********|
//  |* FILTRE *|
//  |**********|

// Restricts input for the given textbox to the given inputFilter.
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
    textbox.addEventListener(event, function () {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}