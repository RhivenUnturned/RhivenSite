<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>[FR] Rhiven | Unturned RP</title>
  <link rel="icon" href="favicon.ico?v=3" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
  @yield('extra-css')
  <style>
    html,
    body {
      background-color: #fdfdff;
      padding-bottom: 60px;
    }
  </style>
</head>

<body>

  <div class="container">
    <div id="app">
      <table class="w-100" style="height: 100vh;">
        <tbody class="mx-auto">
          <tr>
            <td class="align-middle">
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
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('extra-js')
</body>

</html>