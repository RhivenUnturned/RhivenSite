@extends('layouts.master')

@section('extra-css')
<style>
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
@endsection

@section('content')
<div class="scaled">
  <figure id="scene">
    <img class="img-fluid" src="/uploads/test_1.png" alt="image-1" data-depth="0.2">
    <img class="img-fluid" src="/uploads/test_2.png" alt="image-2" data-depth="0.25">
    <div class="para-text" data-depth="0.4">
      <h1 class="para-title" style="left: 25%; top: 25%; position: absolute; color: #fff;">Wow, ce chantier est très sécurisé !</h1>
    </div>
    <img class="img-fluid" src="/uploads/test_3.png" alt="image-3" data-depth="0.5">
  </figure>
</div>
@endsection

@section('extra-js')
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
@endsection