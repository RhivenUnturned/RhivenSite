@extends('layouts.master')

@section('title')
Forum: Discutez, partagez ! | Rhiven Unturned
@endsection

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>

  @foreach($category_by_tag as $tag)
  <div class="card mb-3">
    <div class="card-header">
      {{ $tag->name }}
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($tag->categories as $category)
      <li class="list-group-item">
        <a href="{{ route('forum.category', $category) }}">{{ $category->name }}</a>
      </li>
      @endforeach
    </ul>
  </div>
  @endforeach

</div>

@endsection