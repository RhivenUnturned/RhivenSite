function redirectionCarte(event) {
  const clicExpoMaison = {
    "adresse": $(event.currentTarget).parent().find("h3").text() || null,
  }
  localStorage.setItem('clicExpoMaison', JSON.stringify(clicExpoMaison));
  window.location.href = "immobilier/carte"; // Direction la carte
}