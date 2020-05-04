@extends('layouts.master')

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>

  <ul class="pl-0">
    @foreach($topics as $topic)
    <li class="list-group-item">
      <a href="{{ route('topics.show', $topic) }}">{{ $topic->title }}</a>
      <p>{{ $topic->body }}</p>
      <div class="d-flex justify-content-between">
        <div>
          <small>Posté {{ $topic->created_at->diffForHumans() }}</small>
        </div>
        <div>
          <span class="badge badge-primary">{{ $topic->user->name }}</span>
        </div>
      </div>
    </li>
    @endforeach
  </ul>

</div>

<div class="d-flex justify-content-center mt-3">
  {{ $topics->links() }}
</div>
@endsection