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
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
  <style>
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

  <div class="bg-white py-4">
    <div class="container">
      <div class="row justify-content-around text-center">
        <div class="col-4">
          <a class="text-dark text-decoration-none" href="/">
            <img class="d-inline-block mr-2" src="{{ asset('uploads/Logo_Rhiven_Transparent.png') }}" alt="Logo du site Rhiven, c'est un rond coloré avec un R blanc à l'intérieur" width="85" height="85">
            <h1 class="d-inline-block align-middle">Rhiven</h1>
          </a>
        </div>
        <div class="col-7 my-auto">
          <ul class="nav justify-content-center">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('blog.index') }}">Actualités</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('forum.index') }}">Forum</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="/zone-secrete" tabindex="-1" aria-disabled="true">Zone secrète</a>
            </li>
            <li class="nav-item">
              <p class="nav-link d-inline-block"> - </p>
            </li>
            <li class="nav-item">
              <p id="rhi-players-connected" class="nav-link d-inline-block pr-1">XX connectés</p>
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="d-inline-block bi bi-circle-fill text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <circle cx="8" cy="8" r="8" />
              </svg>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <hr class="mt-0 mb-3">
  <div id="app">

    <div class="container-xl my-4 py-4">
      <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselIndicators" data-slide-to="1"></li>
          <li data-target="#carouselIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="scaled my-4">
              <figure class="scene">
                <img class="img-fluid" src="/uploads/test_1.png" alt="image-1" data-depth="0.2">
                <img class="img-fluid" src="/uploads/test_2.png" alt="image-2" data-depth="0.25">
                <div class="para-text" data-depth="0.4">
                  <h2 class="para-title" style="left: 25%; top: 25%; position: absolute; color: #fff;">Wow, ce chantier est très sécurisé !</h2>
                </div>
                <img class="img-fluid" src="/uploads/test_3.png" alt="image-3" data-depth="0.5">
              </figure>
            </div>
          </div>
          <div class="carousel-item">
            <div class="scaled my-4">
              <figure class="scene">
                <img class="img-fluid" src="/uploads/test_1.png" alt="image-1" data-depth="0.2">
                <img class="img-fluid" src="/uploads/test_2.png" alt="image-2" data-depth="0.25">
                <div class="para-text" data-depth="0.4">
                  <h2 class="para-title" style="left: 25%; top: 25%; position: absolute; color: #fff;">Wow, ce chantier est très sécurisé !</h2>
                </div>
                <img class="img-fluid" src="/uploads/test_3.png" alt="image-3" data-depth="0.5">
              </figure>
            </div>
          </div>
          <div class="carousel-item">
            <div class="scaled my-4">
              <figure class="scene">
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
        <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Précédent</span>
        </a>
        <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Suivant</span>
        </a>
      </div>
    </div>
  </div>
  @yield('footer', View::make('layouts.footer'))

  <script src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript">
    let animated = false,
      rhiPlayersConnected = document.getElementById('rhi-players-connected'),
      rhiStats = document.querySelector('#rhi-stats'),
      toAnimate = document.getElementsByClassName('to-animate');

    var isInViewport = function(elem) {
      var bounding = elem.getBoundingClientRect();
      return (
        bounding.top >= 0 &&
        bounding.left >= 0 &&
        bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    };


    var scenes = document.getElementsByClassName("scene");
    for (var i = 0; i < scenes.length; i++) {
      new parallax(scenes[i], {
        relativeInput: true,
        hoverOnly: true,
        invertX: true,
        limitY: 50,
        limitX: 50,
        frictionX: 0.08,
      });
    }

    axios
      .get("/livemap/server")
      .then(function(response) {
        rhiPlayersConnected.innerHTML = rhiPlayersConnected.innerHTML
          .replace(/XX/g, response.data.Server.PlayersOnline);
        if (response.data.Server.PlayersOnline === '1') {
          rhiPlayersConnected.innerHTML = rhiPlayersConnected.innerHTML
            .replace(/connectés/g, 'connecté');
        } else if (response.data.Server.PlayersOnline === '0') {
          rhiPlayersConnected.nextElementSibling.classList.add('text-danger').remove('text-success');
        }
      })
      .catch(function(error) {
        console.log(error);
      })
  </script>
</body>

</html>