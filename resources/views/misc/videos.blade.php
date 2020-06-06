@extends('layouts.master')

@php
$path = storage_path() . "/app/public/videos.json";
$json = json_decode(file_get_contents($path), true);

usort($json, function($a, $b) {
return $b['snippet']['publishedAt'] <=> $a['snippet']['publishedAt'];
  });
  @endphp

  @section('content')
  <p>
    <a class="btn btn-primary" data-toggle="collapse" href="#videosExplanations" role="button" aria-expanded="false" aria-controls="collapseExample">Faire apparaître ma vidéo sur cette page</a>
  </p>
  <div class="collapse" id="videosExplanations">
    <div class="card card-body">
      Vous souhaitez faire apparaître votre vidéo sur cette page ? Il vous suffit de poster une vidéo sur Youtube avec comme sujet les serveurs Rhiven, et elle sera automatiquement ajoutée à la liste !
    </div>
  </div>

<hr>

  <div class="card-columns">
    @foreach($json as $item)
    <div class="card invitation-parent">
      <a href="https://www.youtube.com/watch?v={{$item['id']['videoId']}}" target="_blank" rel="noopener noreferrer">
        <div class="invitation-container">
          <img class="card-img-top" src="{{ $item['snippet']['thumbnails']['medium']['url'] }}" alt="Minitature vidéo youtube sur Rhiven">
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