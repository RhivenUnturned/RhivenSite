@extends('layouts.master')

@section('extra-js')
{!! NoCaptcha::renderJs('fr') !!}
@endsection

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Poster un nouveau sujet</h1>
  <p class="text-muted text-center">dans la catégorie "{{ $category->name }}"</p>

  <form action="{{ route('topics.store', $category) }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="title">Titre du sujet</label>
      <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title">
      @error('title')
      <div class="invalid-feedback">{{ $errors->first('title') }}</div>
      @enderror
    </div>
    <div class="form-group">
      <label for="body">Contenu du sujet</label>
      <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="5"></textarea>
      @error('body')
      <div class="invalid-feedback">{{ $errors->first('body') }}</div>
      @enderror
    </div>
    <div class="form-group">
      {!! NoCaptcha::display() !!}
      @if ($errors->has('g-recaptcha-response'))
      <span class="help-block">
        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
      </span>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Créer mon sujet</button>
  </form>
</div>
@endsection