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

  $overdraft = isset(auth()->user()->bank->properties['overdraft'][0]) ? auth()->user()->bank->properties['overdraft'][0] : 0;
}}
@endphp

@section('content')

<div class="row">
  <div class="col-lg">
    <div class="bank-panel m-0">
      <h2 class="text-uppercase">Mes comptes ({{ count($accounts) }})</h2>
      <div class="row ml-0">
        <div class="col-sm bank-border-left mb-2">
          <p class="m-0 bank-panel-info-title">Solde total</p>
          <p class="m-0 bank-panel-info">{{ number_format($sumBalance, 2, ',', ' ') }} €</p>
        </div>
        <div class="col-sm bank-border-left mb-2">
          <p class="m-0 bank-panel-info-title">Emprunt total</p>
          <p class="m-0 bank-panel-info">{{ number_format($sumCredit, 2, ',', ' ') }} €</p>
        </div>
      </div>
    </div>
    <ul class="list-group bank-list">
      @foreach($accounts as $account)
      <li class="list-group-item p-4">
        <div class="d-flex justify-content-between">
          <p class="m-0">{{ $account->accountName }}</p>
          <div>
            @if($account->balance >= $overdraft)
            <p class="m-0 d-inline">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
            @else
            <p class="m-0 d-inline text-danger">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
            @endif
            <a href="{{ route('bank.show', $account->accountId) }}"><i class="bank-icon icon-next"></i></a>
          </div>
        </div>
      </li>
      @endforeach
    </ul>

    @if (session('error'))
    <div class="alert alert-danger mt-1">{{ session('error') }}</div>
    @endif

    <!-- Modal CREATE -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="createModalLabel">Créer un compte</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body">
            <form class="pt-2" action="{{ route('bank.store') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="account_name">Nom du compte</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="account_name" name="name" maxlength="80" placeholder="Nom du nouveau compte" required>
                @error('name')
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @enderror
              </div>
              Un nouveau compte sera immédiatement créé.
              <div class="bank-modal-footer">
                <div class="d-flex justify-content-between">
                  <button class="bank-button bank-button-colored px-2 mr-3">Créer</button>
                  <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button class="bank-button bank-button-colored mt-2" data-toggle="modal" data-target="#createModal">Créer un nouveau compte</button>
    </div>

    <ul class="list-group bank-list my-3">
      @foreach($credits as $credit)
      @unless ($credit->pivot->created_at->addDays($credit->properties['period'])->diffInSeconds(Carbon\Carbon::now(), false) > 0)
      <li class="list-group-item p-4">
        <div class="d-flex justify-content-between">
          <p class="m-0">{{ $credit->name }} - {{ $credit->bank->acronym }}</p>
          <div class="text-right row no-gutters w-50">
            <div class="col-lg-12">
              <div class="col-md-2 p-0 float-right ml-2">
                <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($credit->bank->name, '-') }}-sm.svg" class="card-img float-left" alt="Logo banque parente du crédit" height="70px" width="70px">
              </div>
              <p class="m-0">{{ number_format($credit->properties['amount'], 2, ',', ' ') }} €</p>
              <p class="m-0"><small>{{ number_format((($credit->properties['amount']+($credit->properties['amount']/100*$credit->properties['rate']))/$credit->properties['period']), 0, ',', ' ') }}€/j - {{ $credit->pivot->created_at->addDays($credit->properties['period'])->diffInDays() }}j</small></p>
            </div>
          </div>
        </div>
      </li>
      @endunless
      @endforeach
    </ul>

  </div>
  <div class="col-lg">
    <div class="bank-card">
      <div class="bank-card-header">
        <h3 class="text-uppercase m-0">Dernières opérations</h3>
      </div>
      <div class="bank-card-body bank-operations-container">
        @if(count($accountsHistory) === 0)
        <ul class="bank-operations-list my-1 text-center">
          <li class="bank-operations-item">
            <p class="m-0">Vous n'avez pas d'historique d'opération</p>
          </li>
        </ul>
        @else
        @foreach ($accountsHistory as $day => $transactions)
        <h4 class="mt-2 bank-text-colored">{{ $day }}</h4>
        <ul class="bank-operations-list mb-3">
          @foreach ($transactions as $transaction)

          @if(floatval(\Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<'))> 0)

            <li class="bank-operations-item">
              <div class="row">
                <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($transaction->lastUpdated)->format('H:i') }}</div>
                <div class="col-auto text-left">{{ \Illuminate\Support\Str::after($transaction->text, ':') }}</div>
                <div class="col text-right font-weight-bold bank-text-colored mr-1">{{ \Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<') }}</div>
              </div>
            </li>

            @else

            <li class="bank-operations-item ">
              <div class="row">
                <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($transaction->lastUpdated)->format('H:i') }}</div>
                <div class="col-auto text-left">{{ \Illuminate\Support\Str::after($transaction->text, ':') }}</div>
                <div class="col text-right font-weight-bold mr-1">{{ \Illuminate\Support\Str::beforeLast(\Illuminate\Support\Str::after($transaction->text, '>'), '<') }}</div>
              </div>
            </li>

            @endif
            
            <hr class="mx-4 my-1">

            @endforeach
        </ul>
        @endforeach
        @endif
      </div>
    </div>
  </div>
</div>

@endsection