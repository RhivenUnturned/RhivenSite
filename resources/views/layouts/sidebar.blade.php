<div class="card text-white bg-dark mb-4">
  <div class="card-body p-1">
    @if (Auth::user()->steamavatar != null)
    <img class="card-img-top float-left w-25 m-1 rounded" src="{{ Auth::user()->steamavatar }}" alt="Card image cap">
    @endif
    <h5 class="card-title">{{ Auth::user()->name }}</h5>
    <h6 class="card-subtitle mb-2">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
    <p class="card-text">Délivrée le: {{ Carbon\Carbon::parse(Auth::user()->created_at)->format('d/m/y') }}</p>
      <a href="#" class="btn btn-outline-light m-1">Modifier</a>
  </div>
</div>
<div class="rounded sticky-top p-3 text-white bg-dark">
  <h5 class="text-primary">Menu</h5>
  <div class="nav flex-column">
    <a href="#" class="nav-link pl-0">Menu 1</a>
    <a href="#" class="nav-link pl-0">Menu 2</a>
    <a href="#" class="nav-link pl-0">Menu 3</a>
  </div>
</div>