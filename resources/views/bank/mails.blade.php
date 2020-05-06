@extends('bank.master')

@php
{{

$sumUnread = 0;
foreach($mails as $mail) {
  if ($mail->read == false) {
    $sumUnread += 1;
  }
}

}}
@endphp

@section('content')
<div class="row">
  <div class="col-lg mb-3 pr-1">
    <div class="bank-main-container" style="height: 100%;">
      <div class="bank-main-header">
        <h3 class="text-uppercase text-center bank-colored-title">Boîte de réception</h3>
      </div>
      <div class="bank-main-body">
        <ul class="bank-operations-list">
          @foreach($mails as $mail)
          @if ($mail->read == false)
          <!-- Modal NON-READ MAIL -->
          <div class="modal fade" id="nonReadModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="nonReadModalLabel{{ $loop->index }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
              <div class="bank-modal">
                <div class="bank-modal-header">
                  <h5 class="bank-colored-title" id="nonReadModalLabel{{ $loop->index }}">{{ $mail->title }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
                </div>
                <div class="bank-modal-body">
                  <div class="d-flex flex-row mb-3">
                    <div>
                      <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($mail->bank->name, '-') }}.svg" class="d-inline" alt="Logo banque parente du crédit" height="100px" width="100px">
                    </div>
                    <div class="pl-2 pt-4">
                      <p class="d-inline">{{ $mail->bank->name }}</p>
                      <p class="d-inline text-muted">{{ '<contact@' . $mail->bank->acronym . '.net>' }}</p>
                      <p class="">À: moi</p>
                    </div>
                  </div>
                  <hr class="mx-4 my-1">
                  <p>{!! nl2br($mail->body) !!}</p>
                </div>
                <div class="bank-modal-footer text-center">
                  <form class="pt-2" action="{{ route('bank.readMail') }}" method="POST">
                    @csrf
                    <input type="number" style="display:none" name="id" value="{{ $mail->id }}">
                    <button class="bank-button bank-button-colored px-2">LU</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <li class="bank-operations-item font-weight-bold">
            <a href="#nonReadModal{{ $loop->index }}">
              <div class="row">
                <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($mail->updated_at)->format('d/m') }}</div>
                <div class="col-auto text-left">{{ $mail->title }}</div>
                <div class="col text-right font-weight-bold bank-text-colored mr-1">
                  <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($mail->bank->name, '-') }}-sm.svg" class="d-inline" alt="Logo banque parente du crédit" height="40px" width="40px">
                  <p class="d-inline align-middle">{{ $mail->bank->name }}</p>
                </div>
              </div>
            </a>
          </li>
          <hr class="mx-4 my-1">

          @else

          <!-- Modal READ MAIL -->
          <div class="modal fade" id="readModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="readModalLabel{{ $loop->index }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
              <div class="bank-modal">
                <div class="bank-modal-header">
                  <h5 class="bank-colored-title" id="readModalLabel{{ $loop->index }}">{{ $mail->title }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
                </div>
                <div class="bank-modal-body">
                  <div class="d-flex flex-row mb-3">
                    <div>
                      <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($mail->bank->name, '-') }}.svg" class="d-inline" alt="Logo banque parente du crédit" height="100px" width="100px">
                    </div>
                    <div class="pl-2 pt-4">
                      <p class="d-inline">{{ $mail->bank->name }}</p>
                      <p class="d-inline text-muted">{{ '<contact@' . $mail->bank->acronym . '.net>' }}</p>
                      <p class="">À: moi</p>
                    </div>
                  </div>
                  <hr class="mx-4 my-1">
                  <p>{!! nl2br($mail->body) !!}</p>
                </div>
                <div class="bank-modal-footer text-center">
                  <button type="button" class="bank-button bank-button-colored px-2" data-dismiss="modal">RETOUR</button>
                </div>
              </div>
            </div>
          </div>

          <li class="bank-operations-item">
            <a href="#readModal{{ $loop->index }}">
              <div class="row">
                <div class="col-2 text-left bank-text-colored">{{ Carbon\Carbon::parse($mail->updated_at)->format('d/m') }}</div>
                <div class="col-auto text-left">{{ $mail->title }}</div>
                <div class="col text-right font-weight-bold bank-text-colored mr-1">
                  <img src="/uploads/banque/{{ Illuminate\Support\Str::slug($mail->bank->name, '-') }}-sm.svg" class="d-inline" alt="Logo banque parente du crédit" height="40px" width="40px">
                  <p class="d-inline align-middle">{{ $mail->bank->name }}</p>
                </div>
              </div>
            </a>
          </li>
          <hr class="mx-4 my-1">

          @endif
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <div class="col-lg-auto mb-3 pl-1">

    <!-- Modal EMPRUNT ENTREPRISE -->
    <div class="modal fade" id="entrepriseModal" tabindex="-1" role="dialog" aria-labelledby="entrepriseModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="entrepriseModalLabel">Objet: Entreprise - Demande d'emprunt</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body bank-font-size-sm">
            <img src="/uploads/banque/{{ Illuminate\Support\Str::slug(auth()->user()->bank->name, '-') }}-sm.svg" class="d-inline" alt="Logo banque parente du crédit" height="50px" width="50px">
            <h4 class="d-inline align-middle">À: {{ auth()->user()->bank->name }}</h4>
            <p class="d-inline align-middle text-muted">{{ '<contact@' . auth()->user()->bank->acronym . '.net>' }}</p>
            <hr class="mx-4 my-1">
            <form class="pt-2" action="{{ route('bank.storeMail') }}" method="POST">
              @csrf
              <div class="row">
                <div class="col-lg-5">
                  <div class="form-group row">
                    <label for="inputCompany" class="col-sm-2 col-form-label">Entreprise</label>
                    <div class="col-sm-10">
                      <input type="text" name="company" class="form-control" id="inputCompany" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputAddress" class="col-sm-2 col-form-label">Adresse du siège</label>
                    <div class="col-sm-10">
                      <input type="text" name="address" class="form-control" id="inputAddress" placeholder="12 Rue de la paix, Adviska" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputPeriod" class="col-sm-2 col-form-label">Étalement</label>
                    <div class="col-sm-10">
                      <input type="number" min="1" max="10" name="period" class="form-control" id="inputPeriod" placeholder="10 (en jours)" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputAmount" class="col-sm-2 col-form-label">Montant</label>
                    <div class="col-sm-10">
                      <input type="currency" name="amount" class="form-control" id="inputAmount" placeholder="89000 (en euros)" required>
                    </div>
                  </div>

                  <p>Un conseiller vous contactera rapidement. Notez que ces démarches ne peuvent être effectuées que par le propriétaire de l'entreprise.</p>
                  <p>Veillez à ce que votre demande soit cohérente avec votre solvabilité. En cas d'impayés, l'entreprise et le propriétaire de celle-ci s'exposent à des poursuites.</p>
                </div>
                <div class="col-lg">
                  <p>Complétez-ici votre demande, avec toutes les informations nécessaires comme l'activité de votre entreprise, sa viabilité et la raison de l'emprunt.</p>
                  <div class="form-group">
                    <label for="inputBody" class="form-label">Corps du message</label>
                    <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="inputBody" placeholder="Bonjour,..." rows="12" required></textarea>
                  </div>
                </div>
              </div>
              <div class="bank-modal-footer">
                <div class="d-flex justify-content-between">
                  <button class="bank-button bank-button-colored px-2 mr-3">Envoyer</button>
                  <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal ASSURANCE -->
    <div class="modal fade" id="assuranceModal" tabindex="-1" role="dialog" aria-labelledby="assuranceModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="bank-modal">
          <div class="bank-modal-header">
            <h5 class="bank-colored-title" id="assuranceModalLabel">Objet: Particulier - Réclamation assurance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
          </div>
          <div class="bank-modal-body bank-font-size-sm">
            <img src="/uploads/banque/{{ Illuminate\Support\Str::slug(auth()->user()->bank->name, '-') }}-sm.svg" class="d-inline" alt="Logo banque parente du crédit" height="50px" width="50px">
            <h4 class="d-inline align-middle">À: {{ auth()->user()->bank->name }}</h4>
            <p class="d-inline align-middle text-muted">{{ '<contact@' . auth()->user()->bank->acronym . '.net>' }}</p>
            <hr class="mx-4 my-1">
            <form class="pt-2" action="{{ route('bank.storeMail') }}" method="POST">
              @csrf


              <!-- Editable table -->
              <div class="card mb-4">
                <div class="card-body">
                  <div id="table" class="table-editable">
                    <span class="table-add float-right mb-3 mr-2"><a href="#!" class="bank-text-colored"><i class="bank-icon icon-plus" style="font-size:25px;"></i></a></span>
                    <table class="table table-bordered table-responsive-md table-striped text-center">
                      <thead>
                        <tr>
                          <th class="text-center">Nom du bien</th>
                          <th class="text-center">Valeur (estimée)</th>
                          <th class="text-center">Preuve de possession (lien)</th>
                          <th class="text-center">Preuve de perte (lien)</th>
                          <th class="text-center">Détails supplémentaires <br> (Date d'achat, lieu de stockage,...)</th>
                          <th class="text-center">Retirer</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="pt-3" contenteditable="true">Voiture de sport</td>
                          <td class="pt-3" contenteditable="true">150000</td>
                          <td class="pt-3" contenteditable="true">https://ibb.co/FYkx6WM</td>
                          <td class="pt-3" contenteditable="true">https://ibb.co/58H8Ssb</td>
                          <td class="pt-3" contenteditable="true">Stockée dans mon garage, porte fermée, achetée hier vers 14h...</td>
                          <td>
                            <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0">Retirer</button></span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- Editable table -->

              <div class="text-center">
                <p>Avant d'envoyer le message, n'oubliez pas de valider vos données à l'aide du bouton ci-dessous.</p>
                <button type="button" id="export-btn" class="bank-button bank-button-colored px-2 w-50">Valider mes données</button>
              </div>

              <input type="text" name="complaint" class="invisible" id="inputComplaint">

              <p>Complétez ici votre demande avec des informations supplémentaires sur la perte des biens.</p>
              <p>NOTE: Vous ne pouvez pas faire une seule demande pour plusieurs évenements.</p>
              <div class="form-group">
                <label for="inputBody" class="form-label">Détails supplémentaires (obligatoires):</label>
                <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="inputBody" placeholder="Bonjour,..." rows="6" required></textarea>
              </div>

              <div class="bank-modal-footer">
                <div class="d-flex justify-content-between">
                  <button class="bank-button bank-button-colored px-2 mr-3">Envoyer</button>
                  <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>





    <div class="bank-card text-center w-100 h-100">
      <div class="bank-card-body">
        <div style="margin: 25px -20px;">
          <button class="bank-button bank-button-colored w-100 px-2" data-toggle="modal" data-target="#entrepriseModal">PRÊT D'ENTREPRISE</button>
        </div>
        <div style="margin: 25px -20px;">
          <button class="bank-button bank-button-colored w-100 px-2" data-toggle="modal" data-target="#assuranceModal">RÉCLAMATION ASSURANCE</button>
        </div>
        @unless ($sumUnread === 0)
        @if ($sumUnread === 1)
        <p class="bank-text-colored">Vous avez 1 nouveau message</p>
        @else
        <p class="bank-text-colored font-weight-bold">Vous avez {{ $sumUnread }} nouveaux messages</p>
        @endif
        @endunless
        <form style="background-color:#1F6386;margin: 50px -20px;" action="{{ route('bank.readMail') }}" method="POST">
          @csrf
          <input type="text" style="display:none" name="id" value="all">
          <button class="bank-button bank-button-colored w-100 px-2">Tout marquer comme lu</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-js')
<script type="text/javascript">
  window.onload = function() {

    $('.bank-operations-list').on('click', 'a', function(e) {
      e.preventDefault();
      $($(this).attr('href')).modal('toggle');
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

    function string_to_slug(str) {
      str = str.replace(/^\s+|\s+$/g, ''); // trim
      str = str.toLowerCase();

      // remove accents, swap ñ for n, etc
      var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
      var to = "aaaaeeeeiiiioooouuuunc------";
      for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
      }

      str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '') // collapse whitespace and replace by ''
        .replace(/-+/g, ''); // collapse dashes

      return str;
    }

    // DÉBUT Tableau

    const $tableID = $('#table');
    const $BTN = $('#export-btn');
    const $EXPORT = $('#inputComplaint');

    const newTr = `
<tr class="hide">
  <td class="pt-3-half" contenteditable="true">Voiture de sport</td>
  <td class="pt-3-half" contenteditable="true">150000</td>
  <td class="pt-3-half" contenteditable="true">https://ibb.co/FYkx6WM</td>
  <td class="pt-3-half" contenteditable="true">https://ibb.co/58H8Ssb</td>
  <td class="pt-3-half" contenteditable="true">Stockée dans mon garage, porte fermée, achetée hier vers 14h...</td>
  <td>
    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Retirer</button></span>
  </td>
</tr>`;

    $('.table-add').on('click', 'i', () => {

      const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

      if ($tableID.find('tbody tr').length === 0) {
        $('tbody').append(newTr);
      }

      $tableID.find('table').append($clone);
    });

    $tableID.on('click', '.table-remove', function() {
      $(this).parents('tr').detach();
    });

    // A few jQuery helpers for exporting only
    jQuery.fn.pop = [].pop;
    jQuery.fn.shift = [].shift;

    $BTN.on('click', () => {

      const $rows = $tableID.find('tr:not(:hidden)');
      const headers = [];
      const data = [];

      // Get the headers (add special header logic here)
      $($rows.shift()).find('th:not(:empty)').each(function() {
        headers.push(string_to_slug($(this).text().toLowerCase()));
      });

      console.log(headers);

      // Turn all existing rows into a loopable array
      $rows.each(function() {
        const $td = $(this).find('td');
        const h = {};

        // Use the headers from earlier to name our hash keys
        headers.forEach((header, i) => {
          h[header] = $td.eq(i).text();
        });

        data.push(h);
      });

      // Output the result
      $EXPORT.val(JSON.stringify(data));
    });

    // FIN tableau

  };
</script>
@endsection