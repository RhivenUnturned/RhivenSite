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
    <p>
      Posté {{ $post->created_at->diffForHumans() }}
      @if($post->created_at < $post->updated_at)
      | Modifié {{ $post->updated_at->diffForHumans() }}
      @endif
    </p>
    <hr>
    <img class="img-fluid rounded" src="{!! !empty($post->image) ? '/uploads/posts/' . $post->image : 'https://placehold.it/750x300' !!}" alt="">
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

@section('extra-js')
<script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "image": "{!! !empty($post->image) ? '/uploads/posts/' . $post->image : 'https://placehold.it/750x300' !!}",
    "discussionUrl": "{{ url()->full() }}",
    "url": "{{ url()->full() }}",
    "keywords": [
      "unturned",
      "roleplay",
      "france",
      "mods",
      "serveur",
      "2020"
    ],
    "dateCreated": "{{ $post->created_at }}",
    "datePublished": "{{ $post->created_at }}",
    "dateModified": "{{ $post->updated_at }}",
    "inLanguage": "fr-FR",
    "isFamilyFriendly": "true",
    "articleSection": "categorie",
    "articleBody": "{!! nl2br($post->body) !!}",
    "mainEntityOfPage": "True",
    "author": {
      "@type": "Person",
      "name": "{{ $post->user->name }}",
      "url": "{{ url("/") }}"
    },
    "creator": {
      "@type": "Person",
      "name": "{{ $post->user->name }}",
      "url": "{{ url("/") }}"
    },
    "publisher": {
      "@type": "Organization",
      "name": "{{ config('app.name') }}",
      "url": "{{ url("/") }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('uploads/Logo_Rhiven.png') }}",
        "width": "1041",
        "height": "1041"
      }
    }
  }
</script>
@endsection