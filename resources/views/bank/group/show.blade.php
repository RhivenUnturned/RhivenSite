@extends('bank.master')

@php
{{
  $sum = 0;
  foreach($accounts as $account) {
   $sum += $account->balance;
  }

  $overdraft = isset(auth()->user()->bank->properties['overdraft'][0]) ? auth()->user()->bank->properties['overdraft'][0] : 0;
}}
@endphp

@section('content')

<div class="row">
  <div class="col-lg">
    <div class="bank-panel m-0">
      <h2 class="text-uppercase">Mes comptes communs ({{ count($accounts) }})</h2>
      <div class="row ml-0">
        <div class="col-sm bank-border-left mb-2">
          <p class="m-0 bank-panel-info-title">Solde total</p>
          <p class="m-0 bank-panel-info">{{ number_format($sum, 2, ',', ' ') }} €</p>
        </div>
        <div class="col-sm bank-border-left mb-2">
          <p class="m-0 bank-panel-info-title">Compte: {{ $activeAccount->accountName }}</p>
          <p class="m-0 bank-panel-info">ID: {{ $activeAccount->accountId }}</p>
        </div>
      </div>
    </div>
    <ul class="list-group bank-list">
      @foreach($accounts as $account)
      @if ($account->accountName === $activeAccount->accountName)
      <li class="list-group-item p-4 bank-bg-active">
        @else
      <li class="list-group-item p-4">
        @endif
        <div class="d-flex justify-content-between">
          <p class="m-0">{{ $account->accountName }}</p>
          @if ($account->balance > $overdraft)
          <p class="m-0 d-inline">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
          @else
          <p class="m-0 text-danger d-inline">{{ number_format($account->balance, 2, ',', ' ') }} €</p>
          @endif
        </div>
      </li>
      @endforeach
    </ul>


    @if (session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <!-- Modal QUIT -->
    <div class="modal fade" id="quitModal" tabindex="-1" role="dialog" aria-labelledby="quitModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="quitModalLabel">QUITTER LE COMPTE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body">
            <p>Vous êtes sur le point de quitter le compte commun <span class="font-weight-bold bank-text-colored">"{{ $activeAccount->accountName }}"</span>. Vous ne pourrez plus le rejoindre à moins d'y être invité à nouveau. Cette action est irréversible.</p>
            <p>Êtes vous sûr de vouloir quitter le compte <span class="font-weight-bold bank-text-colored">"{{ $activeAccount->accountName }}"</span> ?</p>
          </div>
          <div class="bank-modal-footer">
            <div class="d-flex justify-content-between">
              <button type="button" class="bank-button bank-button-colored px-2 mr-3" data-dismiss="modal">NON</button>
              <form action="{{ route('bank.groupQuit', $activeAccount->accountId) }}" method="POST" style="display: contents;">
                @csrf
                <button class="bank-button bank-button-colored px-2 ml-3">QUITTER</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal DESTROY -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="deleteModalLabel">SUPPRIMER UN COMPTE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body">
            <p>Vous êtes sur le point de supprimer votre compte <span class="font-weight-bold bank-text-colored">"{{ $activeAccount->accountName }}"</span>. Votre compte doit être vide, et cette action est irréversible.</p>
            <p>Êtes vous sûr de vouloir supprimer <span class="font-weight-bold bank-text-colored">"{{ $activeAccount->accountName }}"</span> ?</p>
          </div>
          <div class="bank-modal-footer">
            <div class="d-flex justify-content-between">
              <button type="button" class="bank-button bank-button-colored px-2 mr-3" data-dismiss="modal">NON</button>
              <form action="{{ route('bank.groupDestroy', $activeAccount->accountId) }}" method="POST" style="display: contents;">
                @csrf
                @method('DELETE')
                <button class="bank-button bank-button-colored px-2 ml-3">SUPPRIMER</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal USERS -->
    <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="usersModalLabel">GÉRER LES UTILISATEURS</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body row">
            <div class="col-lg-6">
                <form method="post" action="{{ route('bank.groupManage', $activeAccount->accountId) }}">
                  @csrf
                  <div class="bank-card-body bank-operations-container mb-3">
                    @foreach ($accountUsers as $accountUser)
                    <input type="checkbox" name="accountUsers[]" value="{{ $accountUser->email }}">{{ $accountUser->name }}<br>
                    @endforeach
                  </div>
                  <small>NOTE: Vous ne pouvez pas retirer du compte un autre administrateur, il doit obligatoirement quitter le compte de lui-même.</small>
                  <p class="mt-2 mb-0">Action à effectuer avec les utilisateurs selectionnés:</p>
                  <div class="form-check form-check-inline bank-font-size-sm">
                    <input class="form-check-input" type="radio" name="manageOption" id="manageOption1" value="remove">
                    <label class="form-check-label" for="manageOption1">Retirer du compte</label>
                  </div>
                  <div class="form-check form-check-inline bank-font-size-sm">
                    <input class="form-check-input" type="radio" name="manageOption" id="manageOption2" value="admin">
                    <label class="form-check-label" for="manageOption2">Promouvoir administrateur</label>
                  </div>
                  <button class="bank-button bank-button-colored my-2 bank-font-size-sm">Mettre à jour</button>
                </form>
            </div>
            <div class="col-lg-6 bank-font-size-sm">
              <form action="{{ route('bank.groupAdd', $activeAccount->accountId) }}" method="POST">
                @csrf
                <div class="form-group mb-1">
                  <label for="user_search">Rechercher un utilisateur</label>
                  <input type="text" class="form-control" id="user_search" aria-describedby="searchHelp" placeholder="Utilisateur" required>
                  <small id="searchHelp" class="form-text text-muted text-left">Veuillez renseigner l'ID ou le nom de l'utilisateur.</small>
                </div>
                <input type="text" class="d-none" name="userToAdd" id="user_steamid" required>
                <button class="bank-button bank-button-colored mb-2">AJOUTER</button>
              </form>
              <p>Chaque utilisateur du compte pourra l'utiliser dans des transactions bancaire comme des transferts ou un retrait d'argent.</p>
              <p>Toutefois, seuls les administrateurs du compte ont accès aux fonction de gestion telles que l'ajout d'utilisateurs ou la suppression du compte.</p>
              <p>N'ajoutez que des personnes de confiance. Vous ne bénéficiez d'aucune protection en cas de litige.</p>
            </div>
          </div>
          <div class="bank-modal-footer text-center">
            <button type="button" class="bank-button bank-button-colored px-2 mr-3" data-dismiss="modal">RETOUR</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal EDIT -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="editModalLabel">Renommer le compte</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body">
            <form class="pt-2" action="{{ route('bank.groupUpdate', $activeAccount->accountId) }}" method="POST">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label for="account_name">Nom du compte</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="account_name" name="name" maxlength="80" value="{{ $activeAccount->accountName }}" placeholder="Nouveau nom du compte" required>
                @error('name')
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @enderror
              </div>
              Votre compte <span class="font-weight-bold bank-text-colored">"{{ $activeAccount->accountName }}"</span> sera immédiatement renommé.
              <div class="bank-modal-footer">
                <div class="d-flex justify-content-between">
                  <button class="bank-button bank-button-colored px-2 mr-3">Renommer</button>
                  <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button class="bank-button bank-button-colored mt-2 w-100" data-toggle="modal" data-target="#quitModal">Quitter</button>
    </div>

    @if(auth()->user()->steamid === $accountOwner)
    <div class="d-flex bd-highlight">
      <div class="flex-fill bd-highlight text-left">
        <button class="col bank-button bank-button-colored mt-3" data-toggle="modal" data-target="#editModal">Renommer</button>
      </div>
      <div class="flex-fill bd-highlight text-center">
        <button class="col bank-button bank-button-colored mt-3" data-toggle="modal" data-target="#usersModal">Utilisateurs</button>
      </div>
      <div class="flex-fill bd-highlight text-right">
        <button class="col bank-button bank-button-colored mt-3" data-toggle="modal" data-target="#deleteModal">Supprimer</button>
      </div>
    </div>
    @endif

    <div class="bank-card mt-3">
      <div class="bank-card-header">
        <h3 class="text-uppercase m-0">Activité hebdomadaire du compte</h3>
      </div>
      <div class="bank-card-body">
        <canvas class="bank-chart" id="bankAccountChart"></canvas>
      </div>
    </div>

  </div>
  <div class="col-lg">
    <div class="bank-main-container">
      <div class="bank-main-header">
        <h3 class="text-uppercase text-center bank-colored-title">Opérations: {{ $activeAccount->accountName }}</h3>
      </div>
      <div class="bank-main-body bank-history-container">
        @foreach ($accountHistory as $day => $transactions)
        <h4 class="mt-3 bank-text-colored">{{ $day }}</h4>
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

@endsection

@section('extra-css')
<link rel="stylesheet" type="text/css" href="{{asset('js/jqueryui/jquery-ui.min.css')}}">
@endsection

@section('extra-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="{{asset('js/jqueryui/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
  var color = Chart.helpers.color;
  var balanceData = {
    labels: [
      @foreach($balanceHistory->reverse() as $history)
      "{{ Carbon\Carbon::parse($history[0]->lastUpdated)->format('d/m/y') }}",
      @endforeach
    ],
    datasets: [{
      backgroundColor: color('#000').alpha(0.7).rgbString(),
      borderColor: color('#000'),
      borderWidth: 2,
      lineTension: 0,
      data: [
        @foreach($balanceHistory->reverse() as $balance => $transactions) {
          {
            floatval($balance)
          }
        },
        @endforeach
      ]
    }]
  };

  window.onload = function() {
    var ctx = document.getElementById('bankAccountChart').getContext('2d');
    window.myChart = new Chart(ctx, {
      type: 'line',
      data: balanceData,
      options: {
        title: {
          display: true,
          text: 'Solde du compte'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
            },
            gridLines: {
              display: false
            }
          }],
          xAxes: [{
            display: false
          }]
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        legend: {
          display: false
        },
        tooltips: {
          mode: 'index',
          callbacks: {
            label: function(tooltipItem) {
              return Number(tooltipItem.yLabel) + " €";
            }
          },
          displayColors: false,
          intersect: false
        }
      }
    });

  };

  var colorNames = Object.keys(window.chartColors);

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  $(document).ready(function() {

    $("#user_search").autocomplete({
      source: function(request, response) {
        // Fetch data
        $.ajax({
          url: "{{route('bank.getUsers')}}",
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
        $('#user_search').val(ui.item.label);
        $('#user_steamid').val(ui.item.value);
        return false;
      }
    });
  });
</script>
@endsection