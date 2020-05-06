@extends('bank.master')

@php
{{
  $sumBalance = 0;
  foreach($accounts as $account) {
   $sumBalance += $account->balance;
  }

  $sumCredit = 0;
  foreach ($credits as $credit) {
  $pivot = DB::table('credit_user')
          ->where('credit_id', $credit->id)
          ->where('user_id', Auth::user()->id)
          ->first();

    $amount = $credit->properties['amount'];
    $period = $credit->properties['period'];
    $rate = $credit->properties['rate'];

    $sumCredit += $amount / $period * Carbon\Carbon::parse($pivot->created_at)->addDays($period)->add(1, 'day')->diffInDays(Carbon\Carbon::now()) - ($amount / $period) + ($amount / $period + ($amount / $period / 100 * $rate));
  }
}}
@endphp

@section('content')
@if($deadline > Carbon\Carbon::now())
<!-- Modal liquidation -->
<div class="modal fade" id="liquidationModal" tabindex="-1" role="dialog" aria-labelledby="liquidationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="bank-modal">
      <div class="bank-modal-header">
        <h5 class="bank-colored-title" id="liquidationModalLabel">Liquidation immédiate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="bank-modal-body">
        <p>Vous êtes sur le point de demander une liquidation immédiate, ce qui signifie qu'une demande de saisie de vos bien sera envoyée à la gendarmerie, et que toute transaction avec vos compte bancaires sera gelée.</p>
        <p>Si la saisie n'est pas validée (pas de domicile ou valeur totale de la saisie inférieure à votre dette), une demande d'enquête sera envoyée à la justice.</p>
        <strong>Êtes vous sûr de vouloir demander une liquidation immédiate ?</strong>
      </div>
      <div class="bank-modal-footer">
        <div class="d-flex justify-content-between">
          <button type="button" class="bank-button bank-button-colored px-2 mr-3" data-dismiss="modal">NON</button>
          <form action="{{ route('bank.liquidation') }}" method="POST" style="display: contents;">
            @csrf
            <button class="bank-button bank-button-colored px-2 ml-3">DEMANDER</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal nova -->
<div class="modal fade" id="novaModal" tabindex="-1" role="dialog" aria-labelledby="novaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="bank-modal">
      <div class="bank-modal-header">
        <h5 class="bank-colored-title" id="novaModalLabel">Faire racheter ma dette</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="bank-modal-body">
        <p>Le Crédit Nova est une entreprise spécialisée dans le rachat de dette. Crédit Nova peut vous proposer un crédit adapté à vos besoin.</p>
        <strong>Souhaitez-vous être redirigé vers le Crédit Nova ?</strong>
      </div>
      <div class="bank-modal-footer">
        <div class="d-flex justify-content-between">
          <button type="button" class="bank-button bank-button-colored px-2 mr-3" data-dismiss="modal">NON</button>
          <a href="{{ route('bank.nova') }}" class="bank-button bank-button-colored px-2 ml-3">OUI</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bank-main-container">
  <div class="bank-main-header">
    <h2 class="text-uppercase text-center bank-colored-title">Accès refusé</h2>
  </div>
  <div class="bank-main-body row">
    <div class="col-lg">
      <p>Bonjour, <br><br> Votre solde total étant de <strong>{{ number_format($sumBalance, 2, ',', ' ') }} €</strong> et cette somme étant inférieure à votre autorisation de découvert, vos comptes ont été bloqués.</p>
      <p>Vous avez jusqu'au <strong>{{ Carbon\Carbon::parse($deadline)->format('d/m/y') }}</strong> à <strong>{{ Carbon\Carbon::parse($deadline)->format('H:i') }}</strong> pour rembourser votre dette avant qu'un huissier ne soit saisi et que la liquidation de vos bien ne soit demandée.</p>
      <p>Pour rembourser votre dette, vous pouvez recevoir des virements d'autres comptes, ou déposer du liquide depuis un distributeur. Vous pouvez également faire appel au <strong>Crédit Nova</strong> qui est une entreprise spécialisée dans le rachat de dettes.</p>
      <p>Cliquez sur chaque option pour obtenir d'avantage de détails.</p>
      <p class="m-0">Montant de la dette en cas de liquidation: <strong class="bank-colored-title">{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }} €</strong></p>
      <small>(Remboursement de vos crédits et du solde négatif)</small>

      <div class="text-center">
        <button class="bank-button bank-button-colored mt-2" data-toggle="modal" data-target="#liquidationModal">Demander le début de la liquidation</button>
      </div>
      <div class="text-center">
        <button class="bank-button bank-button-colored mt-2" data-toggle="modal" data-target="#novaModal">Demander une consolidation de dette</button>
      </div>
    </div>

    <div class="col-lg">
      <div class="bank-card">
        <div class="bank-card-header">
          <h3 class="text-uppercase m-0">Dernières opérations</h3>
        </div>
        <div class="bank-card-body bank-operations-container">
          @foreach ($accountsHistory as $day => $transactions)
          <h4 class="mt-2 bank-text-colored">{{ $day }}</h4>
          <ul class="bank-operations-list mb-3">
            @foreach ($transactions as $transaction)

            @if (floatval(\Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<'))> 0)

              <li class="bank-operations-item">
                <div class="row">
                  <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($transaction->lastUpdated)->format('H:i') }}</div>
                  <div class="col-auto text-left">{{ \Illuminate\Support\Str::after($transaction->text, ':') }}</div>
                  <div class="col text-right font-weight-bold bank-text-colored mr-1">{{ \Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<') }}</div>
                </div>
              </li>
              <hr class="mx-4 my-1">

              @else

              <li class="bank-operations-item ">
                <div class="row">
                  <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($transaction->lastUpdated)->format('H:i') }}</div>
                  <div class="col-auto text-left">{{ \Illuminate\Support\Str::after($transaction->text, ':') }}</div>
                  <div class="col text-right font-weight-bold mr-1">{{ \Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<') }}</div>
                </div>
              </li>
              <hr class="mx-4 my-1">

              @endif

              @endforeach
          </ul>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@else
<div class="bank-main-container">
  <div class="bank-main-header">
    <h2 class="text-uppercase text-center bank-colored-title">Liquidation initiée</h2>
  </div>
  <div class="bank-main-body row">
    <div class="col-lg-8">
      <p>Vous n'avez pas été en capacité de couvrir vos dettes dans les délais qui vous ont été accordés.<br />Vos comptes ont été gelés, vos crédits annulés et la justice saisie.</p>
      <p>Vous pourrez suivre la procédure initiée ici.</p>
      <h3 class="text-uppercase text-center bank-colored-title my-4">Votre dette est de {{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }} €</h3>
      <p>Vous retrouverez un accès à vos comptes et à toutes les fonctionnalités de votre banque dès que votre dette aura été remboursée.</p>
    </div>
    <div class="col-lg text-center">
      <img src="/uploads/banque/bank-overdraft-process-{{ strtolower(auth()->user()->bank->acronym) }}.png" alt="Procédure saisie">
    </div>
  </div>
</div>
@endif

@endsection