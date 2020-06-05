@extends('layouts.master')

@php
$path = storage_path() . "\app\public/videos.json";
$json = json_decode(file_get_contents($path), true);
@endphp

@section('content')

<div class="card-columns">
  @foreach($json['items'] as $item)
  <div class="card">
    <img class="card-img-top" src="{{ $item['snippet']['thumbnails']['medium']['url'] }}" alt="Minitature vidéo youtube sur Rhiven">
    <div class="card-body">
      <h5 class="card-title">{{ htmlspecialchars_decode($item['snippet']['title'], ENT_QUOTES) }}</h5>
      <p class="card-text">{{ $item['snippet']['description'] }}</p>
      <p class="card-text"><small class="text-muted">Sortie {{ Carbon\Carbon::parse($item['snippet']['publishedAt'])->diffForHumans() }}</small></p>
    </div>
  </div>
  @endforeach
</div>

@endsection