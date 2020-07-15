@extends('layouts.master')

@section('content')

<div class="row align-items-center">
  <div class="col-11 mx-auto">
    <h1 class="my-4 text-center">Dernières actus</h1>

    @foreach ($posts as $post)
    <div class="card mb-4">
      <img class="card-img-top" alt="Couverture de l'article '{{ $post->title }}'" src="{!! !empty($post->image) ? $post->image : 'https://placehold.it/750x300' !!}" srcset="https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=250 250w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=300 300w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=463 463w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=628 628w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=752 752w">
      <div class="card-body">
        <h2 class="card-title text-center">{{ $post->title }}</h2>
        <p class="card-text"> {{ strip_tags(\Illuminate\Support\Str::limit($post->body, $limit = 280, $end = '...')) }} </p>
        <a href="articles/{{ $post->id }}" class="btn btn-primary">Lire la suite</a>
      </div>
      <div class="card-footer text-muted">
        Posté {{ $post->created_at->diffForHumans() }} par <strong class="text-danger">{{ $post->user->name }}</strong>
      </div>
    </div>
    @endforeach

  </div>
</div>

@endsection