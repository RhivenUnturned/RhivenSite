@extends('immo.master')

@php
function type($adress) {
if ($adress->commerce === 1) {
return "Commerce";
} else if ($adress->appartement === 1) {
return "Appartement";
} else if ($adress->maison === 1) {
return "Maison";
} else if ($adress->industrie === 1) {
return "Industrie";
} else {
return "Inconnu";
}
}

$adresses = $adresses->shuffle()->slice(0, 6);
@endphp

@section('content')
<div class="zone-recherche">
  <form method="post" class="formulaire-recherche">
    <input type="text" class="formulaire-barre-recherche" id="form-adresse" name="adresse" placeholder="Adresse (ex. 'Rue Deneuve')">
    <input type="text" class="formulaire-barre-recherche" id="form-quartier" name="quartier" placeholder="Quartier (ex. 'Brandol')">
    <select class="formulaire-barre-recherche" id="form-ville" name="ville">
      <option value="" selected disabled hidden>Ville</option>
      <option value="adviska">Adviska</option>
      <option value="groviska">Groviska</option>
      <option value="montigny">Montigny</option>
    </select>
    <label class="formulaire-boite-parent" id="boite-form-appartement">Appartement
      <input type="checkbox" name="appartement" id="form-appartement" checked>
      <span class="checkmark"></span>
    </label>
    <label class="formulaire-boite-parent" id="boite-form-industrie">Industrie
      <input type="checkbox" name="industrie" id="form-industrie" checked>
      <span class="checkmark"></span>
    </label>
    <label class="formulaire-boite-parent" id="boite-form-maison">Maison
      <input type="checkbox" name="maison" id="form-maison" checked>
      <span class="checkmark"></span>
    </label>
    <label class="formulaire-boite-parent" id="boite-form-commerce">Commerce
      <input type="checkbox" name="commerce" id="form-commerce" checked>
      <span class="checkmark"></span>
    </label>
    <label class="formulaire-etoile-parent" id="boite-form-offres">Offres spéciales
      <input class="star" type="checkbox" name="offres" id="form-offres" checked>
    </label>
    <input type="text" class="formulaire-barre-recherche" id="form-prix-min" placeholder="Prix min" name="prix-min" pattern="[0-9]+">
    <input type="text" class="formulaire-barre-recherche" id="form-prix-max" placeholder="Prix max" name="prix-max">
    <button type="button" id="form-envoi" name="recherche-maison">RECHERCHER</button>
  </form>
</div>

<div class="expo-maison">
  @foreach($adresses as $adress)
  <div class="expo-carte" id="carte-{{ $loop->index }}">
    <div class="expo-haut" onclick="redirectionCarte(event)">
      <img src="/uploads/immobilier/maisons/{{ Illuminate\Support\Str::slug($adress->image, '-') }}.jpg" class="expo-image" alt="Image de la maison en vente">
      <div class="expo-invitation">Voir sur la carte</div>
    </div>
    <div class="expo-contenu">
      <h3>{{ $adress->adresse }}</h3>
      <p class="expo-description">{{ $adress->description }}</p>
      <div>
        <p class="expo-type">{{ type($adress) }}</p>
        @if($adress->libre === 1)
        <p class="expo-statut">Libre</p>
        @else
        <p class="expo-statut statut-occupe">Occupé</p>
        @endif
        <a href="{{ route('immo.achat', $adress->id) }}" title="Accéder à la page d'achat">
          <div id="dir-achat">
            <p class="expo-prix">{{ number_format(($adress->prix - ($adress->prix / 100  * ($adress->offre * -1))), 0, ',', ' ') }}€</p>
            <i class="icone-custom expo-caddie">&#xe802;</i>
          </div>
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/immo/immo-main.js') }}"></script>
<script type="text/javascript">
  setInputFilter(document.getElementById("form-prix-min"), function(value) {
    return /^-?\d*[.,]?\d{0,2}$/.test(value);
  });
  setInputFilter(document.getElementById("form-prix-max"), function(value) {
    return /^-?\d*[.,]?\d{0,2}$/.test(value);
  });

  $('document').ready(function() {

    // Envoi du formulaire 
    // Check browser support
    if (typeof(Storage) !== "undefined") {

      function envoi() {

        const paramRecherche = {
          "adresse": $("#form-adresse").val() || null,
          "quartier": $("#form-quartier").val() || null,
          "ville": $("#form-ville").val()
        }

        const paramExtra = {
          "appartement": $('#form-appartement').prop('checked') ? '1' : null,
          "industrie": $('#form-industrie').prop('checked') ? '1' : null,
          "maison": $('#form-maison').prop('checked') ? '1' : null,
          "commerce": $('#form-commerce').prop('checked') ? '1' : null,
          "offre": !$('#form-offres').prop('checked') ? '-1' : '0',
          "prixmin": $("#form-prix-min").val() || '0',
          "prixmax": $("#form-prix-max").val() || '9999999999'
        }

        localStorage.setItem('paramRecherche', JSON.stringify(paramRecherche));
        localStorage.setItem('paramExtra', JSON.stringify(paramExtra));
        window.location.href = "immobilier/carte"; // Direction la carte
      }

      $(document).on("keypress", "input", function(e) { // Utilisation touche "entrée"
        if (e.which == 13) {
          envoi()
        }
      });

      $("#form-envoi").on("click", function() { // Utilisation bouton "Rechercher"
        envoi()
      });
    }
  });
</script>
@endsection