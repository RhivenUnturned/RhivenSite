@extends('layouts.master')

@section('content')
<div class="container">
  <div class="livemaps"></div>
</div>

<div class="author"><small>Livemaps v0.3.2.1 par <a href="mailto:nexis@nexisrealms.com">Nexis</a> et <a href="mailto:ceansbzh@gmail.com">Ceans</a></small></div>
@endsection

@section('extra-css')
<link href="{{asset('css/map.css')}}" rel="stylesheet">
@endsection

@section('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{asset('js/carte/velocity.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/carte/velocity.ui.js')}}" type="text/javascript"></script>
<script src="{{asset('js/carte/map.js')}}?v={!! time() !!}" type="text/javascript"></script>
<script>
  init();
</script>
@endsection