@extends('layouts.master')

@section('content')
<div class="map-parent">
  <img id="map-background" class="img-fluid" src="" alt="Carte du serveur Rhiven RP">
  <div class="map-player">test</div>
</div>
@endsection

@section('extra-css')
<link href="{{asset('css/map.css')}}" rel="stylesheet">
@endsection

@section('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{asset('js/carte/map.js')}}" type="text/javascript"></script>
<script>
  init();
</script>
@endsection