@extends('layouts.master')

@section('content')
  <h1>Modifier mon profil</h1>
  <hr>

  <form action="{{ route('user.update') }}" method="POST">
    @csrf
    @method('PATCH')
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
      <label for="firstname" class="col-lg-3 control-label">Prénom RP:</label>
      <div class="col-lg-8">
        <input id="firstname" class="form-control @error('firstname') is-invalid @enderror" type="text" name="firstname" value="{{ Auth::user()->firstname }}">
        @error('firstname')
        <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label for="lastname" class="col-lg-3 control-label">Nom RP:</label>
      <div class="col-lg-8">
        <input id="lastname" class="form-control @error('lastname') is-invalid @enderror" type="text" name="lastname" value="{{ Auth::user()->lastname }}">
        @error('lastname')
        <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
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
        <input id="password" class="form-control @error('password') is-invalid @enderror" aria-describedby="passwordHelp" type="password" name="password">
        @error('password')
        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        @enderror
        <small id="passwordHelp" class="form-text text-muted">Laissez ce champ vide si vous ne souhaitez pas changer de mot de passe.</small>
      </div>
    </div>

    <div class="form-group">
      <label for="password_confirmation" class="col-lg-3 control-label">Confirmation du mot de passe:</label>
      <div class="col-lg-8">
        <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation">
        @error('password_confirmation')
        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-8">
        <button type="submit" class="btn btn-primary">{{ __('Sauvegarder') }}</button>
        <span></span>
        <a class="ml-4" href="{{ URL::previous() }}">Annuler</a>
      </div>
    </div>
    <hr>
  </form>
@endsection