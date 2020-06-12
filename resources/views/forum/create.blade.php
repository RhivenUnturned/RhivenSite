@extends('layouts.master')

@section('extra-css')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 mx-auto mb-3">
  <h1 class="my-4 text-center">Poster un nouveau sujet</h1>
  <p class="text-muted text-center">dans la catégorie "{{ $category->name }}"</p>

  <form id="forum-form" action="{{ route('topics.store', $category) }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="title">Titre du sujet</label>
      <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title') }}">
      @error('title')
      <div class="invalid-feedback">{{ $errors->first('title') }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="body">Contenu du sujet</label>
      <div id="editor-container">
        {!! old('body') !!}
      </div>
      <input name="body" type="hidden">
      @error('body')
      <div class="invalid-feedback d-block">{{ $errors->first('body') }}</div>
      @enderror
    </div>

    <input name="quill" type="hidden">

    <div class="form-group">
      {!! NoCaptcha::display() !!}
      @if ($errors->has('g-recaptcha-response'))
      <span class="help-block">
        <strong class="text-danger">{{ $errors->first('g-recaptcha-response') }}</strong>
      </span>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Créer mon sujet</button>
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