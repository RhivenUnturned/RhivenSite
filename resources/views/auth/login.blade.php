@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 py-5 border">
            <h4 class="pb-4 text-center">{{ __('Accédez à votre espace personnel') }}</h4>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adresse Email') }}</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mot de passe') }}</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Se souvenir de moi') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Se connecter') }}
                        </button>

                        @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4 py-2 bg-dark text-white rounded">
            <div class="card-body text-center">
                <img src="{{ asset('uploads/connexion.svg') }}" style="width:40%">
                <h2 class="py-3">Connexion</h2>
                <p class="text-justify">Vous êtes enfin de retour ! On vous a préparé de quoi vous retaper un peu.</p>
            </div>
        </div>
    </div>
</div>
@endsection