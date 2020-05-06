@extends('bank.master')

@section('content')

<div class="bank-main-container">
  <div class="bank-main-header">
    <h3 class="text-uppercase text-center bank-colored-title">Banques et offres bancaires</h3>

    @if (session('error'))
    <div class="alert alert-danger mt-1">{{ session('error') }}</div>
    @endif
  </div>
  <div class="bank-main-body text-center">
      <div class="row">
        <div class="col-md pr-4 border-right">
          <img src="/uploads/banque/fastwallet-lg.svg" alt="Logo FastWallet" height="100px" width="250px">
          <p class="mt-2">FastWallet permet à une entreprise ou un utilisateur disposant d'une adresse email d'envoyer et de recevoir des paiements en ligne de manière pratique, sécurisée et peu coûteuse.</p>
          <p>La taille de notre réseau et l'acceptation grandissante de notre produit nous ont permis de devenir l'un des principaux réseaux de paiement pour les sites marchants.</p>
          <p class="mb-4">Contrairement à la plupart des offres des services de paiement en ligne, nous vous proposons également une carte bancaire et des prêts à la consommation.</p>

          <a href="/uploads/banque/BrochureFW.pdf" target="_blank" type="button" class="bank-button bank-button-colored px-2 my-3" style="border-radius:20px;">Brochure</a>
          @if(auth()->user()->bank->name != 'FastWallet')
          <button type="button" class="bank-button bank-button-colored px-2" data-toggle="modal" data-target="#fastWalletModal">Choisir l'offre</button>
          @else
          <button type="button" class="bank-button px-2" disabled>Offre actuelle</button>
          @endif
        </div>
        <div class="col-md px-4">
          <img src="/uploads/banque/brastal-united-credit-lg.svg" alt="Logo Brastal United Credit" height="100px" width="250px">
          <p class="mt-2">S’appuyant sur un modèle diversifié et intégré, Brastal United Credit allie solidité financière, dynamique d’innovation et stratégie de croissance durable afin d’être le partenaire de confiance de ses clients, engagé dans les transformations positives du monde.</p>
          <p>La banque de détail à l'international, l'assurance et les services financiers aux entreprises, avec des réseaux présents en Europe, en Russie et en Afrique et des métiers spécialisés leaders dans leurs marchés.</p>
          <p class="mb-4">Nos services comprennent la vente d'actions et les investissements nationaux et internationaux.</p>

          <a href="/uploads/banque/BrochureBUC.pdf" target="_blank" type="button" class="bank-button bank-button-colored px-2 my-3" style="border-radius:20px;">Brochure</a>
          @if(auth()->user()->bank->name != 'Brastal United Credit')
          <button type="button" class="bank-button bank-button-colored px-2" data-toggle="modal" data-target="#brastalModal">Choisir l'offre</button>
          @else
          <button type="button" class="bank-button px-2" disabled>Offre actuelle</button>
          @endif
        </div>
        <div class="col-md pl-4 border-left">
          <img src="/uploads/banque/imperial-bank-lg.svg" alt="Logo Imperial Bank" height="100px" width="250px">
          <p class="mt-2">Imperial Bank est l’un des premiers groupes de services bancaires et financiers au monde.</p>
          <p>Nous sommes au service de plus de 40 millions de clients à travers nos activités.</p>
          <p>L’offre Premium s’adresse aux grands clients du Groupe Imperial Bank pour lesquels une approche globale sur les métiers de banque de financement et d’investissement, d’asset servicing et de gestion de fortune peut être proposée.</p>
          <p class="mb-4">Viennent s'ajouter nos offres des placements financiers avancés, dont l'achat d'actions.</p>

          <a href="/uploads/banque/BrochureIMPERIAL.pdf" target="_blank" type="button" class="bank-button bank-button-colored px-2 my-3" style="border-radius:20px;">Brochure</a>
          @if(auth()->user()->bank->name != 'Imperial Bank')
          <button type="button" class="bank-button bank-button-colored px-2" data-toggle="modal" data-target="#imperialModal">Choisir l'offre</button>
          @else
          <button type="button" class="bank-button px-2" disabled>Offre actuelle</button>
          @endif
        </div>
      </div>
  </div>
</div>

<!-- Modal FASTWALLET -->
<div class="modal fade" id="fastWalletModal" tabindex="-1" role="dialog" aria-labelledby="fastWalletModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="bank-modal">
      <div class="bank-modal-header">
        <h5 class="bank-colored-title" id="fastWalletModalLabel">Changer de banque</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
      </div>
      <div class="bank-modal-body text-center">
        <img src="/uploads/banque/fastwallet-lg.svg" alt="Logo FastWallet" height="100px" width="250px">
        <p class="mt-2">L'ouverture d'un compte pour cette offre est gratuite, sans frais journaliers et sans engament.</p>
        <strong>Changer de banque est une action lourde administrativement, et peut vous nuire si vous n'avez pas les capacités financières de supporter les frais.<br><br></strong>
        <strong>Consultez les différentes offres disponibles dans le détail, et ne prenez pas cette action à la légère.</strong>
      </div>
      <div class="bank-modal-footer">
        <div class="d-flex justify-content-between">
          <form class="pt-2" action="{{ route('bank.changeBank', 1) }}" method="POST" style="display: contents;">
            @csrf
            <button class="bank-button bank-button-colored px-2 mr-3">CONFIRMER</button>
          </form>
          <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">ANNULER</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal BRASTAL UNITED CREDIT -->
<div class="modal fade" id="brastalModal" tabindex="-1" role="dialog" aria-labelledby="brastalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="bank-modal">
      <div class="bank-modal-header">
        <h5 class="bank-colored-title" id="brastalModalLabel">Changer de banque</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
      </div>
      <div class="bank-modal-body text-center">
        <img src="/uploads/banque/brastal-united-credit-lg.svg" alt="Logo Brastal United Credit" height="100px" width="250px">
        <p class="mt-2">Cette offre vous coûtera 750€ à l'ouverture du compte, 24€ de frais journaliers et vous serez tenu d'un engagement de trois jours. Cette offre débloque assurance, épargne, les investissements et les ventes d'actions.</p>
        <strong>Changer de banque est une action lourde administrativement, et peut vous nuire si vous n'avez pas les capacités financières de supporter les frais.<br><br></strong>
        <strong>Consultez les différentes offres disponibles dans le détail, et ne prenez pas cette action à la légère.</strong>
      </div>
      <div class="bank-modal-footer">
        <div class="d-flex justify-content-between">
          <form class="pt-2" action="{{ route('bank.changeBank', 2) }}" method="POST" style="display: contents;">
            @csrf
            <button class="bank-button bank-button-colored px-2 mr-3">CONFIRMER</button>
          </form>
          <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">ANNULER</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal IMPERIAL BANK -->
<div class="modal fade" id="imperialModal" tabindex="-1" role="dialog" aria-labelledby="imperialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="bank-modal">
      <div class="bank-modal-header">
        <h5 class="bank-colored-title" id="imperialModalLabel">Changer de banque</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="bank-font-size-lg" aria-hidden="true">&times;</span> </button>
      </div>
      <div class="bank-modal-body text-center">
        <img src="/uploads/banque/imperial-bank-lg.svg" alt="Logo Imperial Bank" height="100px" width="250px">
        <p class="mt-2">Cette offre vous coûtera 9700€ à l'ouverture du compte, 559€ de frais journaliers et vous serez tenu d'un engagement de cinq jours. Cette offre débloque des investissements plus étendus et la vente d'actions.</p>
        <strong>Changer de banque est une action lourde administrativement, et peut vous nuire si vous n'avez pas les capacités financières de supporter les frais.<br><br></strong>
        <strong>Consultez les différentes offres disponibles dans le détail, et ne prenez pas cette action à la légère.</strong>
      </div>
      <div class="bank-modal-footer">
        <div class="d-flex justify-content-between">
          <form class="pt-2" action="{{ route('bank.changeBank', 3) }}" method="POST" style="display: contents;">
            @csrf
            <button class="bank-button bank-button-colored px-2 mr-3">CONFIRMER</button>
          </form>
          <button type="button" class="bank-button bank-button-colored px-2 ml-3" data-dismiss="modal">ANNULER</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection