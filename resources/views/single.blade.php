@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-lg-10 mx-auto">
    <br>
    <h3 class="mt-4">
      {{ $post->title }}
      <span class="lead">par <strong class="text-danger">{{ $post->user->name }}</strong></span>
    </h3>
    <hr>
    <p>Posté {{ $post->created_at->diffForHumans() }}</p>
    <hr>
    <img class="img-fluid rounded" src="{!! !empty($post->image) ? '/uploads/posts/' . $post->image : 'http://placehold.it/750x300' !!}" alt="">
    <hr>
    <div>
      <p>{!! nl2br($post->body) !!}</p>
      <hr>
      <br>
    </div>

    @auth
    <Comments :post-id='@json($post->id)' :user-name='@json(auth()->user()->name)'>
    </Comments>
    @endauth
  </div>
</div>
@endsection