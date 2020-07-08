@php

if(Auth::user()->character->sex === "Homme") {
$otherSex = "Femme";
} elseif(Auth::user()->character->sex === "Femme") {
$otherSex = "Homme";
} else {
$otherSex = "Inconnu";
}

@endphp

@extends('layouts.master')

@section('content')
<h1>Modifier mon profil</h1>

<form action="{{ route('user.update') }}" method="POST">
  @csrf
  @method('PATCH')

  <hr>
  <h2 class="mb-4">Informations de mon compte</h2>

  <div class="form-group">
    <label for="name" class="col-lg-3 control-label">Pseudo:</label>
    <div class="col-lg-8">
      <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ Auth::user()->name }}">
      @error('name')
      <div class="invalid-feedback">{{ $errors->first('name') }}</div>
      @enderror
    </div>
  </div>

  <div class="form-group">
    <label for="email" class="col-lg-3 control-label">Adresse email:</label>
    <div class="col-lg-8">
      <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ Auth::user()->email }}">
      @error('email')
      <div class="invalid-feedback">{{ $errors->first('email') }}</div>
      @enderror
    </div>
  </div>

  <div class="form-group">
    <label for="password" class="col-lg-3 control-label">Nouveau mot de passe:</label>
    <div class="col-lg-8">
      <input id="password" class="form-control @error('password') is-invalid @enderror" aria-describedby="passwordHelp" type="password" name="password" autocomplete="off">
      @error('password')
      <div class="invalid-feedback">{{ $errors->first('password') }}</div>
      @enderror
      <small id="passwordHelp" class="form-text text-muted">Laissez ce champ vide si vous ne souhaitez pas changer de mot de passe.</small>
    </div>
  </div>

  <div class="form-group">
    <label for="password_confirmation" class="col-lg-3 control-label">Confirmation du mot de passe:</label>
    <div class="col-lg-8">
      <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" autocomplete="off">
      @error('password_confirmation')
      <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
      @enderror
    </div>
  </div>

  <hr>
  <h2 class="mb-4">Personnage RP</h2>

  <div class="form-group">
    <label for="firstname" class="col-lg-3 control-label">Prénom:</label>
    <div class="col-lg-8">
      <input id="firstname" class="form-control @error('firstname') is-invalid @enderror" type="text" name="firstname" value="{{ Auth::user()->character->firstname }}">
      @error('firstname')
      <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
      @enderror
    </div>
  </div>

  <div class="form-group">
    <label for="lastname" class="col-lg-3 control-label">Nom:</label>
    <div class="col-lg-8">
      <input id="lastname" class="form-control @error('lastname') is-invalid @enderror" type="text" name="lastname" value="{{ Auth::user()->character->lastname }}">
      @error('lastname')
      <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
      @enderror
    </div>
  </div>

  <div class="form-group">
    <label for="age" class="col-lg-3 control-label">Âge:</label>
    <div class="col-lg-8">
      <input id="age" class="form-control @error('age') is-invalid @enderror" type="number" min="1" max="120" name="age" value="{{ Auth::user()->character->age }}">
      @error('age')
      <div class="invalid-feedback">{{ $errors->first('age') }}</div>
      @enderror
    </div>
  </div>

  <div class="form-check mt-2">
    <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="sexRadio1" value="{{ Auth::user()->character->sex }}" checked>
    <label class="form-check-label" for="sexRadio1">
      {{ Auth::user()->character->sex }}
    </label>
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="sexRadio2" value="{{ $otherSex }}">
    <label class="form-check-label" for="sexRadio2">
      {{ $otherSex }}
    </label>
  </div>
  @error('sex')
  <div class="invalid-feedback">{{ $errors->first('sex') }}</div>
  @enderror

  <div class="form-group mt-2">
    <label for="storyInput" class="col-lg-3 control-label">Histoire:</label>
    <div class="col-lg-8">
      <textarea class="form-control @error('story') is-invalid @enderror" aria-describedby="storyHelp" id="storyInput" name="story" maxlength="1500" rows="6">{{ Auth::user()->character->story }}</textarea>
      @error('story')
      <div class="invalid-feedback">{{ $errors->first('story') }}</div>
      @enderror
      <small id="storyHelp" class="form-text text-muted">1500 caractères maximum</small>
    </div>
  </div>

  <hr>
  <h2 class="mb-4">Comptes liés</h2>

  <p>Pour modifier un compte lié, veuillez contacter un membre de l'équipe sur Discord, ou envoyer un mail à l'adresse suivante: <a href="mailto:ceansbzh@gmail.com">ceansbzh@gmail.com</a></p>

  <hr>
  <div class="form-group">
    <label class="col-md-3 control-label"></label>
    <div class="col-md-8">
      <button type="submit" class="btn btn-primary">{{ __('Sauvegarder') }}</button>
      <span></span>
      @if( URL::previous() != URL::current() )
      <a class="ml-4" href="{{ URL::previous() }}">Annuler</a>
      @else
      <a class="ml-4" href="{{ route('home') }}">Annuler</a>
      @endif
    </div>
  </div>
</form>
@endsection