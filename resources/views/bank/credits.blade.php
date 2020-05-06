@extends('bank.master')

@php
{{
  $sumBalance = 0;
  foreach($accounts as $account) {
   $sumBalance += $account->balance;
  }

  $sumCredit = 0;
  foreach($credits as $credit) {
   $sumCredit += $credit->properties['amount'];
  }

  $dailyCredit = 0;
  foreach($credits as $credit) {
   $dailyCredit += ($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate'])/$credit->properties['period']);
  }

  $dailyIncome = 0;
  foreach($balancehistory as $balance => $transactions) {
    $dailyIncome += $balance;
  }

  $dailySolvency = ($dailyIncome / 6 - $dailyCredit);

}}
@endphp

@section('content')

<div class="row">
  <div class="col-lg">

    <div class="bank-panel m-0">
      <h2 class="text-uppercase">Crédits et emprunts ({{ count($credits) }})</h2>
      <div class="col-sm bank-border-left mb-2">
        <p class="m-0 bank-panel-info-title">Total des prêts</p>
        <p class="m-0 bank-panel-info">{{ number_format($sumCredit, 2, ',', ' ') }} €</p>
      </div>
    </div>

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bank-title">
      <p class="m-0">Mes crédits</p>
    </div>

    <ul class="list-group bank-list my-3">
      @if(count($credits) === 0)
      <li class="list-group-item p-2 text-center">
        <p class="m-0">Vous n'avez pas de crédit en cours</p>
      </li>
      @else
      @foreach($credits as $credit)
      @unless ($credit->pivot->created_at->addDays($credit->properties['period'])->diffInSeconds(Carbon\Carbon::now(), false) > 0)
      <!-- Modal CREDIT -->
      <div class="modal fade" id="reimbursementModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="reimbursementModalLabel{{ $loop->index }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="bank-modal">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title text-uppercase" id="reimbursementModalLabel{{ $loop->index }}">Rembourser l'emprunt</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body">
              <li class="list-group-item p-4">
                <div class="d-flex justify-content-between">
                  <div>
                    <p class="m-0">{{ $credit->name }} - {{ $credit->bank->acronym }}</p>
                  </div>
                  <div class="text-right row no-gutters w-50">
                    <div class="col-lg-12">
                      <div class="col-md-2 p-0 float-right ml-2">
                        <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($credit->bank->name, '-') }}.svg" class="card-img float-left" alt="Logo banque parente du crédit" height="70px" width="70px">
                      </div>
                      <p class="m-0">{{ number_format($credit->properties['amount'], 2, ',', ' ') }} €</p>
                      <p class="m-0"><small>{{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']), 0, ',', ' ') }}€/j - Expire dans {{ $credit->pivot->created_at->addDays($credit->properties['period'])->shortAbsoluteDiffForHumans() }}</small></p>
                    </div>
                  </div>
                </div>
              </li>
              <p class="my-3">En remboursant votre crédit en une seule fois, vous économiserez les intêrets de la période d'emprunt non-consommée.</p>
              <ul class="list-group bank-list mt-1">
                <li class="list-group-item px-3 pb-2 d-flex justify-content-between">
                  <p class="bank-font-size-sm">Montant restant à rembourser:</p>
                  <p class="bank-text-colored font-weight-bolder">{{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period'])*($credit->pivot->created_at->addDays($credit->properties['period'])->add(1, 'day')->diffInDays(Carbon\Carbon::now())), 2, ',', ' ') }} €</p>
                </li>
                <li class="list-group-item px-3 pb-2 d-flex justify-content-between">
                  <p class="bank-font-size-sm">Taux d'interêt du crédit:</p>
                  <p class="bank-text-colored font-weight-bolder">{{ $credit->properties['rate'] }}%</p>
                </li>
                <li class="list-group-item px-3 pb-2 d-flex justify-content-between">
                  <p class="bank-font-size-sm">Économies réalisées en remboursant immédiatement:</p>
                  <p class="bank-text-colored font-weight-bolder">
                    {{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period'])*($credit->pivot->created_at->addDays($credit->properties['period'])->add(1, 'day')->diffInDays(Carbon\Carbon::now())) - ($credit->properties['amount']/$credit->properties['period']*$credit->pivot->created_at->addDays($credit->properties['period'])->add(1, 'day')->diffInDays(Carbon\Carbon::now()) - ($credit->properties['amount']/$credit->properties['period']) + ($credit->properties['amount']/$credit->properties['period'] + ($credit->properties['amount']/$credit->properties['period']/100*$credit->properties['rate']))), 2, ',', ' ') }} €
                  </p>
                </li>
                <li class="list-group-item px-3 pb-2 d-flex justify-content-between">
                  <p class="bank-font-size-sm">Montant total à rembourser immédiatement:</p>
                  <p class="bank-text-colored font-weight-bolder">{{ number_format($credit->properties['amount']/$credit->properties['period']*$credit->pivot->created_at->addDays($credit->properties['period'])->add(1, 'day')->diffInDays(Carbon\Carbon::now()) - ($credit->properties['amount']/$credit->properties['period']) + ($credit->properties['amount']/$credit->properties['period'] + ($credit->properties['amount']/$credit->properties['period']/100*$credit->properties['rate'])), 2, ',', ' ') }} €</p>
                </li>
              </ul>
            </div>
            <div class="bank-modal-footer">
              <div class="d-flex justify-content-between">
                <form action="{{ route('bank.payCredit') }}" method="POST" style="display: contents;">
                  @csrf
                  <input type="text" style="display:none" name="name" value="{{ $credit->name }}">
                  <button class="bank-button bank-button-colored px-2 mx-3">REMBOURSER</button>
                </form>
                <button type="button" class="bank-button bank-button-colored w-25 px-2 ml-4" data-dismiss="modal">RETOUR</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <li class="list-group-item p-4">
        <div class="d-flex justify-content-between">
          <div>
            <p class="m-0">{{ $credit->name }} - {{ $credit->bank->acronym }}</p>
            <button class="bank-button bank-button-colored mt-2 p-0" data-toggle="modal" data-target="#reimbursementModal{{ $loop->index }}"><small>Rembourser</small></button>
          </div>
          <div class="text-right row no-gutters w-50">
            <div class="col-lg-12">
              <div class="col-md-2 p-0 float-right ml-2">
                <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($credit->bank->name, '-') }}.svg" class="card-img float-left" alt="Logo banque parente du crédit" height="70px" width="70px">
              </div>
              <p class="m-0">{{ number_format($credit->properties['amount'], 2, ',', ' ') }} €</p>
              <p class="m-0"><small>{{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']), 0, ',', ' ') }}€/j - Expire dans {{ $credit->pivot->created_at->addDays($credit->properties['period'])->shortAbsoluteDiffForHumans() }}</small></p>
            </div>
          </div>
        </div>
      </li>
      @endunless
      @endforeach
      @endif
    </ul>

  </div>
  <div class="col-lg">
    <div class="bank-main-container">
      <div class="bank-main-header">
        <h3 class="text-uppercase text-center bank-colored-title">Demander un crédit</h3>
      </div>
      <div class="bank-main-body">
        @foreach(auth()->user()->bank->credits as $credit)
        @unless(strpos($credit->name, 'Entreprise') !== false )
        <!-- Modal CREDIT -->
        <div class="modal fade" id="creditModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="creditModalLabel{{ $loop->index }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="bank-modal">
              <div class="bank-modal-header">
                <h5 class="bank-colored-title text-uppercase" id="creditModalLabel{{ $loop->index }}">Souscrire à un emprunt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
              </div>
              <div class="bank-modal-body row">
                <div class="col-lg-7">
                  <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($credit->bank->name, '-') }}.svg" class="float-left" alt="Logo banque parente du crédit" height="180px" width="180px">
                  <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">{{ $credit->name }}</h3>
                  <p class="text-right m-0">{{ number_format($credit->properties['amount'], 2, ',', ' ') }}€ sur {{ $credit->properties['period'] }} jours</p>
                  <p class="text-right text-muted m-0">TAUX: {{ $credit->properties['rate'] }}%</p>
                  <p class="text-right align-bottom m-0">TOTAL: {{ number_format(($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate'])), 2, ',', ' ') }} €</p>
                  <p class="text-right align-bottom mb-4">VERSEMENTS: {{ $credit->properties['period'] }} x {{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']), 2, ',', ' ') }}€</p>

                  <p class="float-left">
                    Souscrire à un nouveau crédit à la consommation, un prêt immobilier, ou encore accepter une offre de rachat de crédit, n’est pas une action à réaliser à la légère.
                  </p>
                  <p class="float-left">
                    En effet, un crédit est encadré par un contrat, et sa signature est un véritable engagement. Un engagement de la part de l’organisme financier, mais aussi de la part de l’emprunteur.
                  </p>
                </div>
                <div class="col-lg-5 bank-box">
                  <h5 class="text-center font-weight-bold mt-2">ÉVALUATION DE VOTRE SITUATION FINANCIERE</h5>
                  <div class="d-flex justify-content-between bank-text-colored">
                    <div class="bank-border-left-colored ml-3 mb-2">
                      <p class="m-0">Solde total</p>
                      <p class="m-0">{{ number_format($sumBalance, 2, ',', ' ') }} €</p>
                    </div>
                    <div class="bank-border-left-colored mr-3 mb-2">
                      <p class="m-0">Emprunt total</p>
                      <p class="m-0">{{ number_format($sumCredit, 2, ',', ' ') }} €</p>
                    </div>
                  </div>
                  <p class="mt-2">Vous avez <strong class="bank-text-colored">{{ count($credits) }}</strong> emprunt(s) engagé(s).</p>
                  <p>
                    Vos revenus journaliers (basés sur les 6 derniers jours) sont de {{ number_format($dailyIncome/6, 2, ',', ' ') }}€/j. En déduisant vos frais journaliers, et les versements de vos emprunts, on peut estimer votre solvabilité à : {{ number_format($dailySolvency, 2, ',', ' ') }}€/j.
                  </p>
                  @if($dailySolvency >= (($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']))
                  <p class="bank-text-colored">Vous êtes éligible au <span class="text-uppercase">{{ $credit->name }}</span> de {{ $credit->bank->name }}.</p>
                  @else
                  <p class="text-danger">Vous n'êtes pas éligible au <span class="text-uppercase">{{ $credit->name }}</span> de {{ $credit->bank->name }}.</p>
                  @endif
                </div>
              </div>
              <div class="bank-modal-footer">
                <div class="d-flex justify-content-between">
                  @if($dailySolvency >= (($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']))
                  <form action="{{ route('bank.addCredit', $credit->name) }}" method="POST" style="display: contents;">
                    @csrf
                    <input type="text" style="display:none" name="name" value="{{ $credit->name }}">
                    <button class="bank-button bank-button-colored px-2 mx-3">VALIDER L'EMPRUNT</button>
                  </form>
                  <button type="button" class="bank-button bank-button-colored w-25 px-2 ml-4" data-dismiss="modal">RETOUR</button>
                  @else
                  <button type="button" class="bank-button bank-button-colored w-100 px-2" data-dismiss="modal">RETOUR</button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mt-2">
          <div class="row no-gutters">
            <div class="col-lg-12">
              <div class="col-md-4 p-0">
                <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($credit->bank->name, '-') }}-sm.svg" class="card-img float-left m-4" alt="Logo banque parente du crédit" style="max-width:65px;">
              </div>
              <div class="card-body">
                <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">{{ $credit->name }}</h3>
                <p class="card-text text-right m-0">{{ number_format($credit->properties['amount'], 2, ',', ' ') }}€ sur {{ $credit->properties['period'] }} jours</p>
                <p class="card-text text-right text-muted mb-4">TAUX: {{ $credit->properties['rate'] }}%</p>
                <p class="card-text align-bottom m-0">TOTAL: {{ number_format(($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate'])), 2, ',', ' ') }} €</p>
                <p class="card-text align-bottom m-0">VERSEMENTS: {{ $credit->properties['period'] }} x {{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']), 2, ',', ' ') }}€</p>
              </div>
            </div>
          </div>
          <div class="card-footer text-center">
            <button class="bank-button bank-button-colored px-2 ml-3" data-toggle="modal" data-target="#creditModal{{ $loop->index }}">Sélectionner</button>
          </div>
        </div>
        @endunless
        @endforeach
      </div>
    </div>

  </div>
</div>

@endsection