@extends('bank.master')

@php
{{
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
<div class="bank-nova-bg text-center bank-font-size-lg">
  <h1 class="text-uppercase text-light py-3 font-weight-bold bank-nova-title" style="letter-spacing: 3px;">Consolidation de dette</h1>
  <img class="d-inline my-4" src="/uploads/banque/credit-nova.svg" alt="Logo du crédit Nova" height="" width="">
  @if (session('error'))
  <div class="alert alert-danger mt-2">{{ session('error') }}</div>
  @endif
  <div class="row m-4 mx-auto bank-nova-body">
    <div class="col-lg">
      <h2 class="text-danger my-4">Votre dette a été estimée à <strong>{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }} €</strong></h2>
      <p class="text-left">Notre service propose de racheter vos dettes et vos emprunts, pour vous permettre de repartir sur de bonne bases.</p>
      <p class="text-left font-weight-bold">En effet, en consolidant vos dettes, vous transformez plusieurs crédits et sommes à rembourser multiples en un seul emprunt.</p>
    </div>
    <div class="col-lg">
      <!-- Modal CONSO 5 -->
      <div class="modal fade" id="creditModal5Days" tabindex="-1" role="dialog" aria-labelledby="creditModal5DaysModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="bank-modal">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title text-uppercase" id="creditModal5DaysModalLabel">Souscrire à un emprunt</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body row">
              <div class="col-lg-7">
                <img src="/uploads/banque/credit-nova.svg" class="float-left" alt="Logo banque parente du crédit" height="180px" width="180px">
                <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">Consolidation: 5 jours</h3>
                <p class="text-right m-0">{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }}€ sur 5 jours</p>
                <p class="text-right text-muted m-0">TAUX: 4,12%</p>
                <p class="text-right align-bottom m-0">TOTAL: {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12)), 2, ',', ' ') }} €</p>
                <p class="text-right align-bottom mb-4">VERSEMENTS: 5 x {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/5, 2, ',', ' ') }}€</p>

                <p class="float-left text-left">
                  Souscrire à un nouveau crédit à la consommation, un prêt immobilier, ou encore accepter une offre de rachat de crédit, n’est pas une action à réaliser à la légère.
                </p>
                <p class="float-left text-left">
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
                @if($dailySolvency >= ((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/5)
                <p class="bank-text-colored">Vous êtes éligible à la consolidation de dette de NOVA.</p>
                @else
                <p class="text-danger">Vous n'êtes pas éligible à la consolidation de dette de NOVA.</p>
                @endif
              </div>
            </div>
            <div class="bank-modal-footer">
              <div class="d-flex justify-content-between">
                @if($dailySolvency >= ((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/5)
                <form action="{{ route('bank.addCredit', 'conso5') }}" method="POST" style="display: contents;">
                  @csrf
                  <input type="text" style="display:none" name="name" value="conso5">
                  <input type="number" style="display:none" name="debt" value="{{ (-$sumBalance + $sumCredit) }}">
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

      <div class="card mt-2 bank-font-size-sm">
        <div class="row no-gutters">
          <div class="col-lg-12">
            <div class="col-md-4 p-0">
              <img src="/uploads/banque/credit-nova.svg" class="card-img float-left mt-4 ml-2" alt="Logo banque parente du crédit">
            </div>
            <div class="card-body">
              <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">Consolidation: 5 jours</h3>
              <p class="card-text text-right m-0">{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }}€ sur 5 jours</p>
              <p class="card-text text-right text-muted mb-4">TAUX: 4,12%</p>
              <p class="card-text text-left align-bottom m-0">TOTAL: {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12)), 2, ',', ' ') }} €</p>
              <p class="card-text text-left align-bottom m-0">VERSEMENTS: 5 x {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/5, 2, ',', ' ') }}€</p>
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <button class="bank-button bank-button-colored px-2 ml-3" data-toggle="modal" data-target="#creditModal5Days">Demander</button>
        </div>
      </div>


      <!-- Modal CONSO 10 -->
      <div class="modal fade" id="creditModal10Days" tabindex="-1" role="dialog" aria-labelledby="creditModal10DaysModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="bank-modal">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title text-uppercase" id="creditModal10DaysModalLabel">Souscrire à un emprunt</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body row">
              <div class="col-lg-7">
                <img src="/uploads/banque/credit-nova.svg" class="float-left" alt="Logo banque parente du crédit" height="180px" width="180px">
                <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">Consolidation: 10 jours</h3>
                <p class="text-right m-0">{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }}€ sur 10 jours</p>
                <p class="text-right text-muted m-0">TAUX: 4,12%</p>
                <p class="text-right align-bottom m-0">TOTAL: {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12)), 2, ',', ' ') }} €</p>
                <p class="text-right align-bottom mb-4">VERSEMENTS: 10 x {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/10, 2, ',', ' ') }}€</p>

                <p class="float-left text-left">
                  Souscrire à un nouveau crédit à la consommation, un prêt immobilier, ou encore accepter une offre de rachat de crédit, n’est pas une action à réaliser à la légère.
                </p>
                <p class="float-left text-left">
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
                @if($dailySolvency >= ((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/10)
                <p class="bank-text-colored">Vous êtes éligible à la consolidation de dette de NOVA.</p>
                @else
                <p class="text-danger">Vous n'êtes pas éligible à la consolidation de dette de NOVA.</p>
                @endif
              </div>
            </div>
            <div class="bank-modal-footer">
              <div class="d-flex justify-content-between">
                @if($dailySolvency >= ((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/10)
                <form action="{{ route('bank.addCredit', 'conso10') }}" method="POST" style="display: contents;">
                  @csrf
                  <input type="text" style="display:none" name="name" value="conso10">
                  <input type="number" style="display:none" name="debt" value="{{ (-$sumBalance + $sumCredit) }}">
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

      <div class="card mt-2 bank-font-size-sm">
        <div class="row no-gutters">
          <div class="col-lg-12">
            <div class="col-md-4 p-0">
              <img src="/uploads/banque/credit-nova.svg" class="card-img float-left mt-4 ml-2" alt="Logo banque parente du crédit">
            </div>
            <div class="card-body">
              <h3 class="card-title text-right m-0 text-uppercase bank-letter-space font-weight-bolder">Consolidation: 10 jours</h3>
              <p class="card-text text-right m-0">{{ number_format((-$sumBalance + $sumCredit), 2, ',', ' ') }}€ sur 10 jours</p>
              <p class="card-text text-right text-muted mb-4">TAUX: 4,12%</p>
              <p class="card-text text-left align-bottom m-0">TOTAL: {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12)), 2, ',', ' ') }} €</p>
              <p class="card-text text-left align-bottom m-0">VERSEMENTS: 10 x {{ number_format(((-$sumBalance + $sumCredit)+((-$sumBalance + $sumCredit)/100*4.12))/10, 2, ',', ' ') }}€</p>
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <button class="bank-button bank-button-colored px-2 ml-3" data-toggle="modal" data-target="#creditModal10Days">Demander</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection