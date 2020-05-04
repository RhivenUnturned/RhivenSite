@extends('bank.master')

@php
{{
  $overdraft = isset(auth()->user()->bank->properties['overdraft'][0]) ? auth()->user()->bank->properties['overdraft'][0] : 0;
}}
@endphp

@section('content')

<div class="row">
  <div class="col-lg">
    <div class="bank-panel">
      <h2 class="text-uppercase">Sélectionnez un émetteur</h2>
    </div>

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="bank-title">
      <p class="m-0">Mes comptes</p>
    </div>
    <ul class="list-group bank-list my-3">
      @foreach($accounts as $account)
      <li class="list-group-item p-4">
        <div class="d-flex justify-content-between">
          <p class="m-0">{{ $account->accountName }}</p>
          @if ($account->balance > $overdraft)
          <p class="m-0">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
          @else
          <p class="m-0 text-danger">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
          @endif
        </div>
      </li>
      @endforeach
    </ul>

    <div class="bank-title">
      <p class="m-0">Mes comptes communs</p>
    </div>
    <ul class="list-group bank-list my-3">
      @foreach($groupAccounts as $groupAccount)
      <li class="list-group-item p-4">
        <div class="d-flex justify-content-between">
          <p class="m-0">{{ $groupAccount->accountName }}</p>
          <p class="m-0">{{ number_format($groupAccount->balance, 2, ',', ' ') }} €</p>
        </div>
      </li>
      @endforeach
    </ul>

  </div>
  <div class="col-lg">
    <div class="bank-main-container">
      <div class="bank-main-header">
        <h3 class="text-uppercase text-center bank-colored-title">Détails</h3>
      </div>
      <div class="bank-main-body">
        <form class="pt-2" action="{{ route('bank.exchange') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="transfer_account">Compte à débiter</label>
            <select class="form-control" name="from" id="transfer_account" required>
              <option value="" disabled selected>--Choisissez un compte--</option>
              @foreach($accounts as $account)
              <option value="{{ $account->accountId }}">{{ $account->accountName }}</option>
              @endforeach
              <option value="" disabled>--Comptes communs--</option>
              @foreach($groupAccounts as $groupAccount)
              <option value="{{ $groupAccount->accountId }}">{{ $groupAccount->accountName }}</option>
              @endforeach
            </select>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="account_search">Destinataire du virement</label>
                <input type="text" class="form-control" id="account_search" aria-describedby="searchHelp" placeholder="Destinataire" required>
                <small id="searchHelp" class="form-text text-muted text-left">Veuillez renseigner l'ID ou le nom du compte.</small>
              </div>
            </div>
            <div class="col">
              <label for="accountid">ID du destinataire</label>
              <input type="text" class="form-control" id='account_id' name="to" placeholder="Ne rien remplir" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="transfer_amount">Montant du virement</label>
            <input type="currency" class="form-control" name="amount" id="transfer_amount" aria-describedby="transferHelp" placeholder="ex: 150.25" required>
            <small id="transferHelp" class="form-text text-muted text-left">Plafond: {{ number_format(auth()->user()->bank->properties['transfer_ceiling']) }} €</small>
          </div>
          <div class="form-group">
            <label for="transfer_message">Message du virement (optionnel)</label>
            <input type="text" class="form-control" id='transfer_message' name="message" maxlength="100" placeholder="ex: Paiement de la commande... (max: 100)">
          </div>
          <p>Attention, ce virement n'est pas réversible.<br>En effectuant ce virement, vous renoncez à toute opposition sur le présent transfert.</p>
          <p>Des frais à un taux de {{ auth()->user()->bank->properties['transfer_costs'] }}% s'appliquent pour un virement à un tiers.</p>

          <div class="form-check text-right">
            <input class="form-check-input" type="checkbox" value="" id="transfer_infos" required>
            <label class="form-check-label" for="transfer_infos">
              J'ai pris connaissance de ces informations
            </label>
          </div>
          <div class="pt-2 text-center">
            <button type="submit" class="bank-button bank-button-colored mt-4">Effectuer un virement</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection

@section('extra-css')
<link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
@endsection

@section('extra-js')
<script src="{{asset('js/jqueryui/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  $(document).ready(function() {

    $("#account_search").autocomplete({
      source: function(request, response) {
        // Fetch data
        $.ajax({
          url: "{{route('bank.getAccounts')}}",
          type: 'post',
          dataType: "json",
          data: {
            _token: CSRF_TOKEN,
            search: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
      select: function(event, ui) {
        $('#account_search').val(ui.item.label);
        $('#account_id').val(ui.item.value);
        return false;
      }
    });

    var currencyInput = document.querySelector('input[type="currency"]');
    var currency = 'EUR';

    currencyInput.addEventListener('focus', onFocus);
    currencyInput.addEventListener('blur', onBlur);

    function localStringToNumber(s) {
      return Number(String(s).replace(/[^0-9.-]+/g, ""));
    }

    function onFocus(e) {
      var value = e.target.value;
      e.target.value = value ? localStringToNumber(value) : '';
    }

    function onBlur(e) {
      var value = e.target.value;

      const options = {
        maximumFractionDigits: 2,
        currency: currency,
        style: "currency",
        currencyDisplay: "symbol"
      }

      e.target.value = value ?
        localStringToNumber(value).toLocaleString(undefined, options) :
        ''
    }

  });
</script>
@endsection