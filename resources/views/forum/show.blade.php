@extends('layouts.master')

@section('content')
<div class="col-md-12 mx-auto">
  <h1 class="my-4 text-center">Forum</h1>

  <div class="d-flex justify-content-between mb-3 mx-1">
    @can('update', $topic)
    <a href="{{ route('topics.edit', $topic) }}" class="btn btn-warning">Modifier</a>
    @endcan
    @can('delete', $topic)
    <form action="{{ route('topics.destroy', $topic) }}" method="POST">
      @csrf
      @method('DELETE')
      <button class="btn btn-danger">Supprimer</button>
    </form>
    @endcan
  </div>

  <div class="card">
    <div class="card-body">
      <h2 class="card-title">{{ $topic->title }}</h1>
        <p>{!! nl2br($topic->body) !!}</p>
        <div class="d-flex justify-content-between">
          <div>
            <small>Posté {{ $topic->created_at->diffForHumans() }}</small>
            @if ($topic->created_at != $topic->updated_at)
            <small class="d-inline mx-1">|</small>
            <small>Édité {{ $topic->updated_at->diffForHumans() }}</small>
            @endif
          </div>
          <div>
            <span class="badge badge-primary">{{ $topic->user->name }}</span>
          </div>
        </div>
    </div>
  </div>
  <hr>
  <h5>Toutes les réponses</h5>
  @forelse ($topic->replies as $reply)
  <div class="card mt-2">
    <div class="card-body">{{ $reply->body }}
      <div class="d-flex justify-content-between mt-2">
        <small>Posté {{ $reply->created_at->diffForHumans() }}</small>
        <span class="badge badge-primary">{{ $reply->user->name }}</span>
      </div>
      @foreach ($reply->replies as $commentReply)
      <div class="ml-4">
        <hr>
        {{ $commentReply->body }}
        <div class="d-flex justify-content-between mt-2">
          <small>Posté {{ $commentReply->created_at->diffForHumans() }}</small>
          <span class="badge badge-primary">{{ $commentReply->user->name }}</span>
        </div>
      </div>
      @endforeach
      @auth
      <hr>
      <button class="btn btn-info btn-sm" id="replyId" onclick="toggleReplyComment('{{ $reply->id }}')">Répondre</button>
      <form class="ml-4 d-none reply-area" action="{{ route('replies.storeReply', $reply) }}" method="post" id="replyCommentForm-{{ $reply->id }}">
        @csrf
        <div class="form-group mt-3">
          <label for="replyComment">Écrire une réponse</label>
          <textarea class="form-control" name="replyComment" id="replyComment" rows="5"></textarea>
          @error('replyComment')
          <div class="invalid-feedback">{{ $errors->first('replyComment') }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Répondre à ce commentaire</button>
      </form>
      @endauth
    </div>
  </div>
  @empty
  <div class="alert alert-info">Soyez le premier à commenter !</div>
  @endforelse

  <form class="reply-area" action="{{ route('replies.store', $topic) }}" method="POST" class="mt-3">
    @csrf
    <div class="form-group">
      <label for="body">Écrire une réponse</label>
      <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="5"></textarea>
      @error('body')
      <div class="invalid-feedback">{{ $errors->first('body') }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Envoyer ma réponse</button>
  </form>
</div>
@endsection

@section('extra-js')
<script>
  window.addEventListener('load', function() {

    window.toggleReplyComment = function(id) {
      var element = document.getElementById('replyCommentForm-' + id);
      element.classList.toggle('d-none');
    }

    $(".reply-area").keypress(function(e) {
      if (e.which == 13 && !e.shiftKey) {
        $(this).closest("form").submit();
        e.preventDefault();
      }
    });
  });
</script>
@endsection