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
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
  <style>
    html,
    body {
      background-color: #fdfdff;
    }

    .invitation-container {
      position: relative;
      text-align: center;
      color: rgb(228, 228, 228);
      cursor: pointer;
    }

    .invitation-container:after {
      content: "\A";
      position: absolute;
      width: 100%;
      height: calc(100% - 3px);
      top: 0;
      left: 0;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      transition: all 0.5s;
      -webkit-transition: all 0.5s;
    }

    .invitation {
      opacity: 0;
      visibility: hidden;
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 1;
      transform: translate(-50%, -50%);
      -webkit-transition: opacity 600ms, visibility 600ms;
      transition: opacity 600ms, visibility 600ms;
      font-weight: 700;
    }

    .invitation-parent:hover .invitation,
    .invitation-parent:hover .invitation-container:after {
      visibility: visible;
      opacity: 1;
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

  <div class="bg-light py-4">
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

    <div class="container-xl my-4">
      <div class="scaled my-4">
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
    <div id="rhi-stats" class="row justify-content-center text-center w-100 bg-light p-4 m-0">
      <div class="col-sm">
        <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-truck" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5v7h-1v-7a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5v1A1.5 1.5 0 0 1 0 10.5v-7zM4.5 11h6v1h-6v-1z" />
          <path fill-rule="evenodd" d="M11 5h2.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5h-1v-1h1a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4.5h-1V5zm-8 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 1a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
          <path fill-rule="evenodd" d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 1a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
        </svg>
        <div class="to-animate mt-1 display-4">30 véhicules</div>
      </div>
      <div class="col-sm">
        <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-tools" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M0 1l1-1 3.081 2.2a1 1 0 0 1 .419.815v.07a1 1 0 0 0 .293.708L10.5 9.5l.914-.305a1 1 0 0 1 1.023.242l3.356 3.356a1 1 0 0 1 0 1.414l-1.586 1.586a1 1 0 0 1-1.414 0l-3.356-3.356a1 1 0 0 1-.242-1.023L9.5 10.5 3.793 4.793a1 1 0 0 0-.707-.293h-.071a1 1 0 0 1-.814-.419L0 1zm11.354 9.646a.5.5 0 0 0-.708.708l3 3a.5.5 0 0 0 .708-.708l-3-3z" />
          <path fill-rule="evenodd" d="M15.898 2.223a3.003 3.003 0 0 1-3.679 3.674L5.878 12.15a3 3 0 1 1-2.027-2.027l6.252-6.341A3 3 0 0 1 13.778.1l-2.142 2.142L12 4l1.757.364 2.141-2.141zm-13.37 9.019L3.001 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026z" />
        </svg>
        <div class="to-animate mt-1 display-4">53 métiers</div>
      </div>
      <div class="col-sm">
        <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-building" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694L1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z" />
          <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z" />
        </svg>
        <div class="to-animate mt-1 display-4">42 bâtiments</div>
      </div>
    </div>

    <div class="container-xl my-4">
      <div class="card-group">
        @foreach($posts as $post)
        <div class="card invitation-parent">
          <a href="{{ route('post.single',[$post->id, $post->slug])}}">
            <div class="invitation-container">
              <img class="card-img-top" alt="Couverture de l'article '{{ $post->title }}'" src="{!! !empty($post->image) ? $post->image : 'https://placehold.it/750x300' !!}" srcset="https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=250 250w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=300 300w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=463 463w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=628 628w, https://awkzqfrgoo.cloudimg.io/v7/{!! $post->image !!}?width=752 752w">
              <div class="invitation">Lire la suite</div>
            </div>
          </a>
          <div class="card-body">
            <a class="text-dark" href="{{ route('post.single',[$post->id, $post->slug])}}">
              <h2 class="card-title">{{ $post->title }}</h2>
            </a>
            <p class="card-text"> {{ strip_tags(\Illuminate\Support\Str::limit($post->body, $limit = 280, $end = '...')) }} </p>
          </div>
          <div class="card-footer text-muted">
            Posté {{ $post->created_at->diffForHumans() }} par <strong class="text-danger">{{ $post->user->name }}</strong>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="text-justify text-light" style="height: 250px; background: url(https://awkzqfrgoo.cloudimg.io/v7/https://i.ibb.co/hH36rMJ/pompe-1.png?width=980) no-repeat center fixed; -webkit-background-size: cover; background-size: cover;">
      <div class="container p-4">
        <p class="lead">
          Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.
        </p>
        <p class="lead">
          Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus. ivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.
        </p>
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

    // Parallax
    var scene = document.getElementById('scene');
    var parallaxInstance = new parallax(scene, {
      relativeInput: true,
      hoverOnly: true,
      invertX: true,
      limitY: 50,
      limitX: 50,
      frictionX: 0.08,
    });

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

    // Animation des comptes
    function animateValue(obj, start = 0, end = null, duration = 3000) {
      if (obj) {

        // save starting text for later (and as a fallback text if JS not running and/or google)
        var textStarting = obj.innerHTML;

        // remove non-numeric from starting text if not specified
        end = end || parseInt(textStarting.replace(/\D/g, ""));
        var range = end - start;

        // no timer shorter than 50ms (not really visible any way)
        var minTimer = 50;

        // calc step time to show all interediate values
        var stepTime = Math.abs(Math.floor(duration / range));

        // never go below minTimer
        stepTime = Math.max(stepTime, minTimer);

        // get current time and calculate desired end time
        var startTime = new Date().getTime();
        var endTime = startTime + duration;
        var timer;

        function run() {
          var now = new Date().getTime();
          var remaining = Math.max((endTime - now) / duration, 0);
          var value = Math.round(end - (remaining * range));
          // replace numeric digits only in the original string
          obj.innerHTML = textStarting.replace(/([0-9]+)/g, value);
          if (value == end) {
            clearInterval(timer);
          }
        }

        timer = setInterval(run, stepTime);
        run();
      }
    }

    ['DOMContentLoaded', 'scroll'].forEach(evt =>
      window.addEventListener(evt, function(event) {
        if (isInViewport(rhiStats)) {
          if (!animated) {
            for (let element of toAnimate) {
              animateValue(element);
            }
            animated = true;
          }
        }
      }, false)
    );
  </script>
</body>

</html>