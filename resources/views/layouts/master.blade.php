<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="L'expérience RP Unturned francophone la plus poussée. Plugins, Mods, Site Internet innovant, découvrez Rhiven !">
  <meta name="author" content="Ceans">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>[FR] Rhiven | Unturned RP</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
  @yield('extra-css')
  <style>
    html,
    body {
      background-color: #fdfdff;
      padding-bottom: 60px;
    }
  </style>
</head>

<body>

  <header class="jumbotron jumbotron-banner jumbotron-banner-nepeta jumbotron-fluid mb-0">
    <div class="container">
      <h1 class="display-2">Rhiven</h1>
    </div>
  </header>

  @yield('navbar', View::make('layouts.navbar'))

  <div class="container">
    @if (Auth::user() != null)
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

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('extra-js')
</body>

</html>