@extends('layouts.master')

@section('extra-css')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

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
    <img class="img-fluid rounded" src="{!! !empty($post->image) ? '/uploads/posts/' . $post->image : 'https://placehold.it/750x300' !!}" alt="Couverture de l'article '{{ $post->title }}' ">
    <hr>
    <div id="article" class="ql-editor h-auto">
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
<script>
  window.lazySizesConfig = window.lazySizesConfig || {};
  window.lazySizesConfig.init = false;
</script>
<script src="https://cdn.scaleflex.it/filerobot/js-cloudimage-responsive/lazysizes.min.js"></script>
<script src="https://cdn.scaleflex.it/plugins/js-cloudimage-responsive/4.5.0/js-cloudimage-responsive.min.js"></script>
<script type="text/javascript">
  const ciResponsive = new window.CIResponsive({
    token: 'awkzqfrgoo',
    lazyLoading: true
  });
  window.lazySizes.init();
</script>
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