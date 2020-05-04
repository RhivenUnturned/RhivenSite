@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Vérifie ton adresse email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un lien de vérification tout neuf viens de t'être envoyé.') }}
                        </div>
                    @endif

                    {{ __('Avant de continuer, merci de vérifier ta boîte mail pour un lien de vérification.') }}
                    {{ __('Si tu n\'a pas reçu de mail') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('clique ici pour en demander un nouveau.') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
