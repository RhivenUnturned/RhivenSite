<div class="card bg-dark">
  @if (Auth::user()->steamavatar != null)
  <img class="card-img-top sidebar-picture img-thumbnail" src="{{ Auth::user()->steamavatar }}" alt="Card image cap">
  @endif
  <div class="card-body">
    <h5 class="card-title">{{ Auth::user()->name }}</h5>
    <h6 class="card-subtitle mb-2">{{ Auth::user()->created_at->toDateTimeString() }}</h6>
    <a href="#" class="card-link">Card link</a>
    <a href="#" class="card-link">Another link</a>
  </div>
</div>