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
@endphp

@section('content')
<div id="boite-achat-image">
  <div id="achat-fond-image" style="background-image: url('/uploads/immobilier/maisons/{{ Illuminate\Support\Str::slug($adress->image, '-') }}.jpg');"></div>
  <img id="achat-image" src="/uploads/immobilier/maisons/{{ Illuminate\Support\Str::slug($adress->image, '-') }}.jpg" alt="Image de la maison en vente">
</div>

<div class="contenu-principal">
  <div class="presentation-maison">
    <h2 class="titre-adresse">{{ $adress->adresse }}</h2>
    <div class="achat-infos-statut-type">
      <p class="expo-type">{{ type($adress) }}</p>
      @if($adress->libre === 1)
      <p class="expo-statut">Libre</p>
      @else
      <p class="expo-statut statut-occupe">Occupé</p>
      @endif
    </div>
    <p class="expo-description achat-description">" {{ $adress->description }} "</p>
    <div class="achat-infos-nombres">

      <p class="expo-portes">Nombre de portes : {{ $adress->nb_porte }}</p>
      <p class="expo-fenetres">Nombre de fenêtres : {{ $adress->nb_fenetre }}</p>
    </div>
  </div>


  <div class="formulaire-achat">
    <h3 class="titre-formulaire-achat">Acheter le bien</h3>
    <p class="">PRIX : {{ number_format(($adress->prix - ($adress->prix / 100  * ($adress->offre * -1))), 0, ',', ' ') }}€</p>
    @if (session('error'))
    <div class="alert alert-danger mt-1">{{ session('error') }}</div>
    @endif
    @if($adress->libre === 1)
    <form class="pt-2" action="{{ route('immo.store', $adress->id) }}" method="POST">
      @csrf
      <button id="form-envoi">ACHETER</button>
    </form>
    @else
    <p class="expo-statut statut-occupe">Occupé</p>
    @endif
  </div>
</div>
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/immo/achat-main.js') }}"></script>
@endsection