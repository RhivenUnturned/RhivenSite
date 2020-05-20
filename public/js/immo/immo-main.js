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

function redirectionCarte(event) {
  const clicExpoMaison = {
    "adresse": $(event.currentTarget).parent().find("h3").text() || null,
  }
  localStorage.setItem('clicExpoMaison', JSON.stringify(clicExpoMaison));
  window.location.href = "immobilier/carte"; // Direction la carte
}

function redirectionAchat(event) {
  const paramAchat = {
    "adresse": $(event.currentTarget).parent().parent().find("h3").text() || null,
  }
  localStorage.setItem('paramAchat', JSON.stringify(paramAchat));
  window.location.href = "immobilier/achat"; // Direction la page d'achat
}