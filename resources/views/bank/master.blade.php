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
  @yield('extra-css')
  <link href="{{ asset('css/banque.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/' . Illuminate\Support\Str::slug(auth()->user()->bank->name, '-') . '.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="container-fluid">
    <div class="row min-vh-100 flex-column flex-lg-row">
      <aside class="col-12 col-lg-2 p-0 flex-shrink-1">
        <div class="bank-info d-flex p-3">
          <div class="w-100 d-flex">
            <img class="float-left mx-2 p-1" src="/uploads/banque/user.svg" alt="Logo utilisateur" height="60" width="60">
            <h3 class="float-left align-middle">{{ auth()->user()->firstname }}<br><strong>{{ auth()->user()->lastname }}</strong></h3>
          </div>
          <div class="flex-shrink-1">
            <a class="bank-home-link" href="/"><i class="bank-icon icon-previous" style="font-size: 40px;color:#fff;"></i></a>
          </div>
        </div>
        <nav class="navbar navbar-expand flex-lg-column flex-row align-items-start py-2">
          <div class="collapse navbar-collapse ">
            <ul class="flex-lg-column flex-row navbar-nav w-100 justify-content-between">
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.index') }}"><i class="bank-icon icon-drawer"></i> <span class="d-none d-lg-inline">Mes comptes</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.groupIndex') }}"><i class="bank-icon icon-network"></i> <span class="d-none d-lg-inline">Comptes communs</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.credits') }}"><i class="bank-icon icon-schedule"></i> <span class="d-none d-lg-inline">Crédits et Emprunts</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.transfer') }}"><i class="bank-icon icon-arrows"></i> <span class="d-none d-lg-inline">Virement</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.mails') }}"><i class="bank-icon icon-letter"></i> <span class="d-none d-lg-inline">Messagerie</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.myBank') }}"><i class="bank-icon icon-table"></i> <span class="d-none d-lg-inline">Ma Banque</span></a>
              </li>
              @if(auth()->user()->hasRole('admin') or auth()->user()->hasJob('banquier'))
              <li class="nav-item">
                <a class="nav-link pl-0 my-1" href="{{ route('bank.administration') }}"><i class="bank-icon icon-gear"></i> <span class="d-none d-lg-inline">Administration</span></a>
              </li>
              @endif
              @if(auth()->user()->bank->acronym === 'IMPERIAL')
              <li class="nav-item bank-logo">
                <img src="/uploads/banque/imperial-bank-white.svg" style="max-width: 240px;" alt="Logo banque parente">
              </li>
              @endif
            </ul>
          </div>
        </nav>
      </aside>
      <main class="col bg-faded py-3 flex-grow-1">
        <div class="pt-3 bank-top-bar d-flex justify-content-between">
          <a href="{{ url()->previous() }}"><i class="bank-icon icon-previous" style="font-size: 40px"></i></a>
          <a href="/" class="pr-3">
            <div class="d-flex justify-content-between text-light text-right">
              <p class="d-inline">RETOUR<br>MENU</p>
              <i class="d-inline bank-icon icon-exit text-light" style="font-size: 42px"></i>
            </div>
          </a>
        </div>
        <div id="app">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('extra-js')
</body>

</html>