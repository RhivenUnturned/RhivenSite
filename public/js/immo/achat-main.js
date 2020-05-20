
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

function redirectionCarte(event) {
  const clicExpoMaison = {
    "adresse": $(event.currentTarget).parent().find("h3").text() || null,
  }
  localStorage.setItem('clicExpoMaison', JSON.stringify(clicExpoMaison));
  window.location.href = "immobilier/carte"; // Direction la carte
}