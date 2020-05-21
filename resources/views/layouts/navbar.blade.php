<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="ip bg-dark">IP: 000.000.000.000</div>
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      <img src="/uploads/Logo_Rhiven_Transparent.png" width="30" height="30" class="d-inline-block align-top" alt="Logo de Rhiven">
      Rhiven
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('forum.index') }}">Forum</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('bank.index') }}">Banque</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('immo.index') }}">Immobilier</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        @auth
        @unless (auth()->user()->unreadNotifications->isEmpty())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ auth()->user()->unreadNotifications->count() }} notification(s)
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            @foreach (auth()->user()->unreadNotifications as $notif)
            <a class="dropdown-item" href="{{ route('topics.showFromNotification', ['topic' => $notif->data['topicId'], 'notification' => $notif->id]) }}"><strong>{{ $notif->data['username'] }}</strong> a posté un commentaire sur "<u>{{ $notif->data['topicTitle'] }}</u>"</a>
            @endforeach
          </div>
        </li>
        @endunless
        @endauth
        @if (Route::has('login'))
        @auth
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Se déconnecter
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">Se connecter</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary" href="{{ route('register') }}">S'inscrire</a>
        </li>
        @endauth
        @endif
      </ul>
    </div>
  </div>
</nav>