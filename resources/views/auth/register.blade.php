@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 py-2 bg-dark text-white rounded">
            <div class="card-body text-center">
                <img src="{{ asset('uploads/inscription.svg') }}" style="width:40%; transform:rotateY(180deg);">
                <h2 class="py-3">Inscription</h2>
                <p class="text-justify">Nous allons avoir besoin de lier votre compte Steam au site. C'est une action unique, qui n'est demandée qu'à l'inscription. <br> Les seules informations que nous récupérons lors de ce lien sont votre SteamID64 et votre image de profil Steam.</p>
            </div>
        </div>
        <div class="col-md-8 py-5 border">
            <h4 class="pb-4">Détails de votre compte</h4>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Pseudo') }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Adresse Email') }}" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" placeholder="{{ __('Prénom RP') }}" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname">
                        @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" placeholder="{{ __('Nom RP') }}" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname">
                        @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Mot de passe') }}" name="password" required autocomplete="new-password">
                    </div>

                    <div class="form-group col-md-6">
                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirmation du mot de passe') }}" name="password_confirmation" required>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-check ml-1">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                <small>En vous inscrivant, vous acceptez notre politique de confidentialité et nos conditions d'utilisation.</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <button type="submit" class="mx-auto btn btn-primary">{{ __('S\'inscrire et lier mon compte Steam') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
@endsection