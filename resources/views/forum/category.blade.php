@extends('layouts.master')

@section('title')
Forum: {{ $category->name }} | Rhiven Unturned
@endsection

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>
  <a class="btn btn-primary mb-3" href="{{ route('topics.create', $category) }}">Créer un sujet</a>

  <div class="card mb-3">
    <div class="card-header">
      {{ $category->name }}
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($topics as $topic)
      <li class="list-group-item">
        <a href="{{ route('topics.show', $topic) }}">{{ $topic->title }}</a>
        <p>{{ strip_tags(\Illuminate\Support\Str::limit($topic->body, $limit = 280, $end = '...')) }} </p>
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

</div>
@endsection