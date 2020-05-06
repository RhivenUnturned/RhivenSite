@extends('bank.master')

@section('content')
<div class="bank-main-container">
  <div class="bank-main-header">
    <h3 class="text-uppercase text-center bank-colored-title">Boîte de réception</h3>
  </div>
  <div class="bank-main-body">
    <ul class="bank-operations-list">
      @foreach($mails as $mail)

      <!-- Modal REFUSE -->
      <div class="modal fade" id="refuseModal{{ $loop->index }}" tabindex="-1" style="z-index:1051;" role="dialog" aria-labelledby="refuseModalLabel{{ $loop->index }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="bank-modal" style="border: 1px solid #000;">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title" id="refuseModalLabel{{ $loop->index }}">Refuser une demande</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body">
              <form class="pt-2" action="{{ route('bank.refuseMail', $mail->id) }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="reasonInput">Raison du refus</label>
                  <input type="text" class="form-control" id="reasonInput" name="reason" placeholder="ex: Manque d'informations/Autre raison..." required>
                </div>
                Un mail expliquant le refus sera immédiatement envoyé à la personne ayant envoyé le message.
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

      @if ($mail->read == false)

      @if (strpos($mail->title, 'assurance') !== false)

      <!-- Modal REIMBURSE INSURANCE -->
      <div class="modal fade" id="insuranceModal{{ $loop->index }}" tabindex="-1" style="z-index:1051;" role="dialog" aria-labelledby="insuranceModalLabel{{ $loop->index }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="bank-modal" style="border: 1px solid #000;">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title" id="insuranceModalLabel{{ $loop->index }}">Accepter la demande</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body">
              <p>
                Rappel des plafonds d'assurance: <br>
                - FastWallet: 0€ <br>
                - Brastal United Credit: 10 000€ <br>
                - Imperial Bank: 100 000€
              </p>
              <form class="pt-2" action="{{ route('bank.acceptMail', $mail->id) }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="amountInput">Montant remboursé</label>
                  <input type="number" class="form-control" id="amountInput" name="amount" placeholder="ex: 15000 (en euros)" required>
                  <small id="transferHelp" class="form-text text-muted text-left">Attention de ne pas dépasser le plafond d'assurance de l'utilisateur</small>
                </div>
                <p>Si vous avez modifié la somme totale demandée à l'origine, expliquez pourquoi ici:</p>
                <div class="form-group">
                  <label for="reasonInput">Raison</label>
                  <input type="text" class="form-control" id="reasonInput" name="reason" placeholder="ex: Plafond de votre assurance/Autre raison..." required>
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

      <!-- Modal NON-READ INSURANCE MAIL -->
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
              <div class="d-flex justify-content-between">
                <button class="bank-button bank-button-colored px-2 mr-3" data-toggle="modal" data-target="#insuranceModal{{ $loop->index }}">Accepter et choisir la somme remboursée</button>
                <button class="bank-button bank-button-colored px-2" data-toggle="modal" data-target="#refuseModal{{ $loop->index }}">Refuser</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      @else

      <!-- Modal CREDIT COMPANY -->
      <div class="modal fade" id="creditModal{{ $loop->index }}" tabindex="-1" style="z-index:1051;" role="dialog" aria-labelledby="creditModalLabel{{ $loop->index }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="bank-modal" style="border: 1px solid #000;">
            <div class="bank-modal-header">
              <h5 class="bank-colored-title" id="creditModalLabel{{ $loop->index }}">Accepter la demande</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="bank-modal-body">
              <p>En acceptant cette demande d'emprunt, vous ajouterez un crédit sur le compte de l'utilisateur. Veuillez choisir les paramêtres de ce crédit.</p>
              <form class="pt-2" action="{{ route('bank.addCredit', $mail->id) }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="amountInput">Montant du crédit</label>
                  <input type="number" step="0.01" min="1" class="form-control" id="amountInput" name="amount" placeholder="ex: 15000 (en euros)" required>
                </div>
                <div class="form-group">
                  <label for="rateInput">Taux du crédit</label>
                  <input type="number" step="0.01" min="0" class="form-control" id="rateInput" name="rate" placeholder="ex: 2.36 (en pourcentage)" required>
                </div>
                <div class="form-group">
                  <label for="periodInput">Période d'étalement</label>
                  <input type="number" class="form-control" id="periodInput" name="period" placeholder="ex: 6 (en jours)" required>
                </div>
                <input type="text" style="display:none" name="name" value="entreprise">
                <input type="number" style="display:none" name="mail" value="{{ $mail->id }}">
                <p>Le crédit sera immédiatement ajouté sur le compte de l'utilisateur.</p>
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

      <!-- Modal NON-READ COMPANY MAIL -->
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
              <div class="d-flex justify-content-between">
                <button class="bank-button bank-button-colored px-2 mr-3" data-toggle="modal" data-target="#creditModal{{ $loop->index }}">Accepter et générer le crédit</button>
                <button class="bank-button bank-button-colored px-2" data-toggle="modal" data-target="#refuseModal{{ $loop->index }}">Refuser</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      @endif

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
@endsection

@section('extra-js')
<script type="text/javascript">
  window.onload = function() {

    $('.bank-operations-list').on('click', 'a', function(e) {
      e.preventDefault();
      $($(this).attr('href')).modal('toggle');
    });

  };
</script>
@endsection