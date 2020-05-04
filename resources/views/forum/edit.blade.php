@extends('layouts.master')

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>
  <h2 class="my-4 text-center">Modifier le sujet "{{ $topic->title }}"</h2>

  <form action="{{ route('topics.update', $topic) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="form-group">
      <label for="title">Titre du sujet</label>
      <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ $topic->title }}">
      @error('title')
      <div class="invalid-feedback">{{ $errors->first('title') }}</div>
      @enderror
    </div>
    <div class="form-group">
      <label for="body">Contenu du sujet</label>
      <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="5">{{ $topic->body }}</textarea>
      @error('body')
      <div class="invalid-feedback">{{ $errors->first('body') }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Éditer mon sujet</button>
  </form>
</div>
@endsection