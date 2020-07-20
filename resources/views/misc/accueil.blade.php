@extends('layouts.master')

@section('extra-css')
<style>
  .scaled {
    /* the parent */
    overflow: hidden;
  }

  .scaled > * {
    /* the children */
    margin: -25px;
  }
</style>
@endsection

@section('content')
<div class="scaled">
    <figure id="scene">
      <img class="img-fluid" src="/uploads/test_1.png" alt="image-1" data-depth="0.2">
      <img class="img-fluid" src="/uploads/test_2.png" alt="image-2" data-depth="0.4">
      <img class="img-fluid" src="/uploads/test_3.png" alt="image-3" data-depth="0.6">
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
  });
</script>
@endsection