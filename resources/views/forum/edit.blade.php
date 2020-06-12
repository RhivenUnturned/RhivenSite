@extends('layouts.master')

@section('extra-css')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>
  <h2 class="my-4 text-center">Modifier le sujet "{{ $topic->title }}"</h2>

  <form id="forum-form" action="{{ route('topics.update', $topic) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="form-group">
      <label for="title">Titre du sujet</label>
      <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ $topic->title }}">
      @error('title')
      <div class="invalid-feedback">{{ $errors->first('title') }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="body">Contenu du sujet</label>
      <div id="editor-container">
        {!! $topic->body !!}
      </div>
      <input name="body" type="hidden">
      @error('body')
      <div class="invalid-feedback d-block">{{ $errors->first('body') }}</div>
      @enderror
    </div>

    <input name="quill" type="hidden">

    <button type="submit" class="btn btn-primary">Éditer mon sujet</button>
  </form>
</div>
@endsection

@section('extra-js')
{!! NoCaptcha::renderJs('fr') !!}
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  var quill = new Quill('#editor-container', {
    modules: {
      toolbar: [
        ['bold', 'italic'],
        ['link', 'blockquote', 'code-block', 'image'],
        [{
          list: 'ordered'
        }, {
          list: 'bullet'
        }]
      ]
    },
    theme: 'snow'
  });

  var form = document.querySelector('#forum-form');
  form.onsubmit = function() {
    // Populate hidden form on submit
    var body = document.querySelector('input[name=body]');
    body.value = quill.root.innerHTML;
    var quillDelta = document.querySelector('input[name=quill]');
    quillDelta.value = JSON.stringify(quill.getContents());
  };
</script>
@endsection