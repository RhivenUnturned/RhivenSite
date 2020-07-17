<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Ceans">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Rhiven | Serveur Unturned RP Francophone')</title>
  <meta name="description" content="@yield('description', 'L\'expérience RP Unturned francophone la plus poussée. Plugins, Mods, Site Internet innovant, découvrez Rhiven !')">
  @yield('extra-head')
  <link rel="icon" href="/favicon.ico" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
  @yield('extra-css')
  <style>
    html,
    body {
      background-color: #fdfdff;
    }
  </style>
</head>

<body>

  <header class="headerImgContainer">
    <h1 class="display-2 centered">Rhiven</h1>
    <picture>
      <source media="(max-width: 576px)" srcset="/uploads/banner-576.webp">
      <source media="(max-width: 768px)" srcset="/uploads/banner-768.webp">
      <source media="(max-width: 992px)" srcset="/uploads/banner-992.webp">
      <source media="(max-width: 1200px)" srcset="/uploads/banner-1200.webp">
      <source media="(min-width: 1201px)" srcset="/uploads/banner-2560.webp">
      <img src="/uploads/banner-1200.webp" alt="La bannière du site, montrant une route passant entre des immeubles Unturned">
    </picture>
  </header>

  @yield('navbar', View::make('layouts.navbar'))

  <div class="container" style="margin-bottom:100px;">
    @if (Auth::user() != null && Request::path() != 'livemap')
    <div class="row">
      <div class="col-md-9" id="app">
        @yield('content')
      </div>
      <div class="col-md-3 p-0">@yield('sidebar', View::make('layouts.sidebar'))</div>
    </div>
    @else
    <div id="app">
      @yield('content')
    </div>
    @endif
  </div>

  @yield('footer', View::make('layouts.footer'))

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('extra-js')
</body>

</html>