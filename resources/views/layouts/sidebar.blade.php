@php

$from = DB::table('unt_bs_multi_accounts')
->where('steamId', auth()->user()->steamid)
->orderby('balance', 'desc')
->first();

@endphp

<div class="card text-white bg-dark mb-4">
  <div class="text-center bg-primary rounded-top" style="letter-spacing: 0.3em; margin: -1px;">RÉPUBLIQUE DE NEPETA</div>
  <div class="card-body p-2">
    @if (Auth::user()->steamavatar != null)
    <img class="card-img-top float-left w-25 mr-1 rounded" src="{{ Auth::user()->steamavatar }}" alt="Card image cap">
    @endif
    <h5 class="card-title">{{ Auth::user()->name }}</h5>
    <h6 class="card-subtitle mb-2">{{ Auth::user()->character->firstname }} {{ Auth::user()->character->lastname }}</h6>
    <div class="d-flex justify-content-between">
      <p class="card-text mb-1">Né le: {{ Carbon\Carbon::parse(Auth::user()->created_at)->subYears(Auth::user()->character->age)->format('d/m/y') }}</p>
      <p class="card-text mb-1">Sexe: {{ Auth::user()->character->sex[0] }}</p>
    </div>
  </div>
  <div class="rounded-bottom p-2" style="letter-spacing: 0.3em; margin: -1px; background-color: #e5e5e5;">
    <a href="{{ route('user.edit') }}" class="btn btn-outline-dark">Modifier</a>
  </div>
</div>

@if(isset($from) && Auth::user()->hasRole('admin'))
<div class="card text-white bg-dark mb-4">
  <div class="card-body p-2">
    <h5 class="card-title">Virement rapide</h5>
    <form class="pt-2" action="{{ route('bank.exchange') }}" method="POST">
      @csrf
      <input type="hidden" name="from" value="{{ $from->accountId }}" readonly>
      <input type="hidden" id="account_id" name="to" readonly>
      <div class="form-group">
        <input type="text" class="form-control" id="account_search" placeholder="Destinataire" required>
      </div>
      <div class="input-group mb-3">
        <input type="currency" class="form-control" name="amount" id="transfer_amount" placeholder="Montant" aria-label="Montant" aria-describedby="info-montant" required>
        <div class="input-group-append">
          <span class="input-group-text" id="info-montant">€</span>
        </div>
      </div>
      @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      <button type="submit" class="mx-auto w-100 btn btn-secondary">Envoyer</button>
    </form>
  </div>
</div>
@endif

@if(isset($from))
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
      var last2 = value.slice(-4, -2);
      if (last2 === '00') {
        value = value.slice(0, -4);
      }
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
@endif