@extends('layouts.master')

@section('content')
<video loop muted autoplay poster="uploads/carte/fbg.jpg" class="fullscreen-bg__video">
  <source src="uploads/carte/tnr1.mp4" type="video/mp4">
</video>

<div class="container">
  <div class="livemaps"></div>
</div>

<div class="author"><small>Livemaps v0.3 by <a href="mailto:nexis@nexisrealms.com">Nexis</a></small></div>
@endsection

@section('extra-css')
<link href="{{asset('css/map.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
@endsection

@section('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{asset('js/carte/velocity.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/carte/velocity.ui.js')}}" type="text/javascript"></script>
<script src="{{asset('js/carte/map.js')}}" type="text/javascript"></script>
<script>
  init();
</script>
@endsection