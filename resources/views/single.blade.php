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
    <img class="img-fluid rounded" alt="Couverture de l'article '{{ $post->title }}'" src="{!! !empty($post->image) ? $post->image : 'https://placehold.it/750x300' !!}" srcset="https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=300 300w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=450 450w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=510 510w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=570 570w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=683 683w">
    <hr>
    <div id="loading" class="text-center my-4">
      <div class="spinner-border text-secondary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <div id="article" class="ql-editor invisible" style="height: 0px;overflow-y:visible;">
      <p>{!! nl2br($post->body) !!}</p>
    </div>

    <hr>

    @auth
    <Comments :post-id='@json($post->id)' :user-name='@json(auth()->user()->name)'>
    </Comments>
    @endauth
  </div>
</div>
@endsection

@section('extra-js')
<script type="text/javascript">
  var articleContainer = document.querySelector("#article");
  window.onload = function() {
    var images = articleContainer.querySelectorAll("p > img");

    for (var i = 0; i < images.length; i++) {
      images[i].parentNode.classList.add('text-center');
    }

    document.querySelector("#loading").classList.add('d-none');
    articleContainer.classList.remove('invisible');
    articleContainer.style.height = 'auto';
  }
</script>
<script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "image": "{!! !empty($post->image) ? $post->image : 'https://placehold.it/750x300' !!}",
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
    "articleBody": "{!! str_replace( '"', "'", strip_tags(nl2br($post->body))) !!}",
    "mainEntityOfPage": "True",
    "author": {
      "@type": "Person",
      "name": "{{ $post->user->name }}",
      "url": "{{ url(" / ") }}"
    },
    "creator": {
      "@type": "Person",
      "name": "{{ $post->user->name }}",
      "url": "{{ url(" / ") }}"
    },
    "publisher": {
      "@type": "Organization",
      "name": "{{ config('app.name') }}",
      "url": "{{ url(" / ") }}",
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