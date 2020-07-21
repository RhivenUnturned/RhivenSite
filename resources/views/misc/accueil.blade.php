<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Ceans">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Rhiven | Serveur Unturned RP Francophone</title>
  <meta name="description" content="L'expérience RP Unturned francophone la plus poussée. Plugins, Mods, Site Internet innovant, découvrez Rhiven !">
  <link rel="icon" href="/favicon.ico" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
  <style>
    html,
    body {
      background-color: #fdfdff;
    }

    /*
 * Header
 */
    .masthead {
      margin-bottom: 2rem;
    }

    .masthead-brand {
      margin-bottom: 0;
    }

    .nav-masthead .nav-link {
      padding: .25rem 0;
      font-weight: 700;
      color: rgba(255, 255, 255, .5);
      background-color: transparent;
      border-bottom: .25rem solid transparent;
    }

    .nav-masthead .nav-link:hover,
    .nav-masthead .nav-link:focus {
      border-bottom-color: rgba(255, 255, 255, .25);
    }

    .nav-masthead .nav-link+.nav-link {
      margin-left: 1rem;
    }

    .nav-masthead .active {
      color: #fff;
      border-bottom-color: #fff;
    }

    @media (min-width: 48em) {
      .masthead-brand {
        float: left;
      }

      .nav-masthead {
        float: right;
      }
    }


    /*
 * Cover
 */
    .cover {
      padding: 0 1.5rem;
    }

    .cover .btn-lg {
      padding: .75rem 1.25rem;
      font-weight: 700;
    }


    .scaled {
      /* the parent */
      overflow: hidden;
    }

    .scaled>* {
      /* the children */
      margin: -25px;
    }

    .para-text {
      width: 100%;
      height: 100%;
    }

    .para-title {
      max-width: 25vw;
      font-size: calc(14px + (35 - 14) * ((100vw - 300px) / (1600 - 300)));

      -webkit-animation: floating-text 2s ease alternate infinite;
      -moz-animation: floating-text 2s ease alternate infinite;
      -ms-animation: floating-text 2s ease alternate infinite;
      -o-animation: floating-text 2s ease alternate infinite;
      animation: floating-text 2s ease alternate infinite;
    }


    @-webkit-keyframes floating-text {
      0% {
        -webkit-transform: translateY(.2em);
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .3em .2em rgba(0, 0, 0, .3);

      }

      100% {
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .5em .5em rgba(0, 0, 0, .3);
      }
    }

    @-moz-keyframes floating-text {
      0% {
        -moz-transform: translateY(.2em);
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .3em .2em rgba(0, 0, 0, .3);

      }

      100% {
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .5em .5em rgba(0, 0, 0, .3);
      }
    }

    @-ms-keyframes floating-text {
      0% {
        -ms-transform: translateY(.2em);
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .3em .2em rgba(0, 0, 0, .3);

      }

      100% {
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .5em .5em rgba(0, 0, 0, .3);
      }
    }

    @-o-keyframes floating-text {
      0% {
        -o-transform: translateY(.2em);
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .3em .2em rgba(0, 0, 0, .3);

      }

      100% {
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .5em .5em rgba(0, 0, 0, .3);
      }
    }

    @keyframes floating-text {
      0% {
        transform: translateY(.2em);
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .3em .2em rgba(0, 0, 0, .3);

      }

      100% {
        text-shadow: 0 0 .5em rgba(255, 255, 255, .4),
          0 0 .2em rgba(0, 0, 0, .3),
          0 .5em .5em rgba(0, 0, 0, .3);
      }
    }
  </style>
</head>

<body>

  <div class="row justify-content-center">
    <div class="col-4 py-5 text-center">
      <img class="d-block mx-auto mb-2" src="{{ asset('uploads/Logo_Rhiven_Transparent.png') }}" alt="" width="100" height="100">
      <h1>Rhiven</h1>
    </div>
    <div class="col-4">
      One of two columns
    </div>
  </div>

  <div class="container-xl my-4">
    <div id="app">
      <div class="scaled">
        <figure id="scene">
          <img class="img-fluid" src="/uploads/test_1.png" alt="image-1" data-depth="0.2">
          <img class="img-fluid" src="/uploads/test_2.png" alt="image-2" data-depth="0.25">
          <div class="para-text" data-depth="0.4">
            <h2 class="para-title" style="left: 25%; top: 25%; position: absolute; color: #fff;">Wow, ce chantier est très sécurisé !</h2>
          </div>
          <img class="img-fluid" src="/uploads/test_3.png" alt="image-3" data-depth="0.5">
        </figure>
      </div>

    </div>
  </div>

  @yield('footer', View::make('layouts.footer'))

  <script src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript">
    var scene = document.getElementById('scene');
    var parallaxInstance = new parallax(scene, {
      relativeInput: true,
      hoverOnly: true,
      invertX: true,
      limitY: 50,
      limitX: 50,
      frictionX: 0.08,
    });
  </script>
</body>

</html>