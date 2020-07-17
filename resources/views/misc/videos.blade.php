@extends('layouts.master')

@php
$path = storage_path() . "/app/public/videos.json";
$json = json_decode(file_get_contents($path), true);

usort($json, function($a, $b) {return $b['snippet']['publishedAt'] <=> $a['snippet']['publishedAt'];});
@endphp

@section('title')
Vidéos de la communauté | Rhiven Unturned
@endsection

@section('description')
Découvrez toutes les vidéos de la communauté Rhiven. Vous êtes un Youtubeur ? Ajoutez votre vidéo à cette page simplement !
@endsection


  @section('content')
  <div class="w-100 p-3 rounded bg-dark text-light">
    <h2 id="pageTitle">
      <button class="btn btn-dark" style="font-size:2rem;font-weight:500;line-height:1.2;" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        Vos vidéos
        <i data-feather="chevron-down"></i>
      </button>
    </h2>
    <div id="collapseOne" class="collapse" aria-labelledby="pageTitle">
      <p>Retrouvez-ici toutes les vidéos concernant la communauté Rhiven et ses différents serveurs. Attention, si une vidéo apparaîssant ici ne nous convient pas, Rhiven se réserve le droit de la retirer.</p>

      <h4>Faire apparaître ma vidéo ici</h4>
      <p>Il vous suffit de poster une vidéo sur Youtube avec comme sujet les serveurs Rhiven, et elle sera automatiquement ajoutée à la liste !</p>
    </div>
  </div>

  <hr>

  <div class="card-columns">
    @foreach($json as $item)
    <div class="card invitation-parent">
      <a href="https://www.youtube.com/watch?v={{$item['id']['videoId']}}" target="_blank" rel="noopener noreferrer">
        <div class="invitation-container">
          <img class="card-img-top" src="{{ $item['snippet']['thumbnails']['medium']['url'] }}" alt="Minitature de la vidéo Youtube {{ htmlspecialchars_decode($item['snippet']['title'], ENT_QUOTES) }}">
          <div class="invitation">Voir la vidéo</div>
        </div>
      </a>
      <div class="card-body">
        <h5 class="card-title">{{ htmlspecialchars_decode($item['snippet']['title'], ENT_QUOTES) }}</h5>
        <p class="card-text"><small class="text-muted">Sortie {{ Carbon\Carbon::parse($item['snippet']['publishedAt'])->diffForHumans() }}</small></p>
      </div>
    </div>
    @endforeach
  </div>

  @endsection

  @section('extra-js')
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace();
  </script>
  @endsection