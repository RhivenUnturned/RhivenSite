@extends('layouts.master')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-4 py-2 bg-dark text-white rounded">
			<div class="card-body text-center">
				<img src="{{ asset('uploads/inscription.svg') }}" style="width:40%; transform:rotateY(180deg);">
				<h2 class="py-3">Inscription</h2>
				<p class="text-justify">Rhiven possède une interaction jeu/site internet encore jamais vue dans la communauté Unturned. Pour en profiter, il vous suffit de suivre ces quelques étapes.</p>
			</div>
		</div>
		<div class="col-md-8 py-5 border">
			<div class="container">
				<div class="multisteps-form">
					<div class="row">
						<div class="col-12 col-lg-8 mx-auto mb-4">
							<div class="multisteps-form__progress">
								<button class="multisteps-form__progress-btn js-active" type="button" title="Infos du compte">Infos du compte</button>
								<button class="multisteps-form__progress-btn" type="button" title="Personnage RP">Personnage RP</button>
								<button class="multisteps-form__progress-btn" type="button" title="Lier mes comptes">Lier mes comptes</button>
								<button class="multisteps-form__progress-btn" type="button" title="Message">Dernière étape</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div id="error-parent" class="col-10 col-lg-8 mx-auto">
						</div>
					</div>
					<div class="row">
						<div class="col-12 m-auto multisteps-form__form">
							<form method="POST" action="{{ route('register') }}" id="registration-form">
								@csrf
								<div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideHorz">
									<h3 class="multisteps-form__title">Informations de votre compte</h3>
									<div class="multisteps-form__content">
										<div class="form-row mt-4">
											<div class="form-group col-md-6">
												<input id="name" type="text" class="form-control multisteps-form__input @error('name') is-invalid @enderror" placeholder="{{ __('Pseudo') }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
												@error('name')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>

											<div class="form-group col-md-6">
												<input id="email" type="email" class="form-control multisteps-form__input @error('email') is-invalid @enderror" placeholder="{{ __('Adresse Email') }}" name="email" value="{{ old('email') }}" required autocomplete="email">
												@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>
										</div>
										<div class="form-row mt-2">
											<div class="form-group col-md-6">
												<input id="password" type="password" class="form-control multisteps-form__input @error('password') is-invalid @enderror" placeholder="{{ __('Mot de passe') }}" name="password" minlength="8" required autocomplete="new-password">
												@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>

											<div class="form-group col-md-6">
												<input id="password_confirmation" type="password" minlength="8" class="form-control multisteps-form__input @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirmation du mot de passe') }}" name="password_confirmation" autocomplete="off" autofill="off" required>
											</div>
										</div>
										<div class="button-row d-flex mt-4">
											<button class="btn btn-primary ml-auto js-btn-next" type="button" title="Suivant">Suivant</button>
										</div>
									</div>
								</div>

								<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
									<h3 class="multisteps-form__title">Votre personnage RP</h3>
									<div class="multisteps-form__content">
										<div class="form-row mt-4">
											<div class="form-group col-md-6">
												<input id="firstname" type="text" class="form-control multisteps-form__input @error('firstname') is-invalid @enderror" placeholder="{{ __('Prénom') }}" name="firstname" value="{{ old('firstname') }}" required autocomplete="given-name">
												@error('firstname')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>

											<div class="form-group col-md-6">
												<input id="lastname" type="text" class="form-control multisteps-form__input @error('lastname') is-invalid @enderror" placeholder="{{ __('Nom') }}" name="lastname" value="{{ old('lastname') }}" required autocomplete="family-name">
												@error('lastname')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>
										</div>

										<div class="form-row mt-4">
											<div class="form-group col-md-6">
												<div class="form-check form-check-inline">
													<input class="form-check-input multisteps-form__input @error('sex') is-invalid @enderror" type="radio" value="Femme" name="sex" id="sexRadio1" required>
													<label class="form-check-label" for="sexRadio1">Femme</label>
												</div>
												<div class="form-check form-check-inline mb-3">
													<input class="form-check-input multisteps-form__input @error('sex') is-invalid @enderror" type="radio" value="Homme" name="sex" id="sexRadio2" required>
													<label class="form-check-label" for="sexRadio2">Homme</label>
													@error('sex')
													<div class="invalid-feedback ml-2">{{ $errors->first('sex') }}</div>
													@enderror
												</div>
											</div>

											<div class="form-group col-md-6">
												<input id="age" class="form-control multisteps-form__input @error('age') is-invalid @enderror" placeholder="{{ __('Âge') }}" type="number" min="1" max="120" name="age" value="{{ old('age') }}" required>
												@error('age')
												<div class="invalid-feedback">{{ $errors->first('age') }}</div>
												@enderror
											</div>
										</div>

										<div class="form-group mt-2">
											<textarea class="form-control multisteps-form__input @error('story') is-invalid @enderror" aria-describedby="storyHelp" id="story" name="story" maxlength="1500" rows="6" placeholder="{{ __('Histoire') }}">{{ old('story') }}</textarea>
											@error('story')
											<div class="invalid-feedback">{{ $errors->first('story') }}</div>
											@enderror
											<small id="storyHelp" class="form-text text-muted">1500 caractères maximum</small>
										</div>

										<div class="button-row d-flex mt-4">
											<button class="btn btn-secondary js-btn-prev" type="button" title="Précédent">Précédent</button>
											<button id="js-btn-submit" class="btn btn-primary ml-auto js-btn-next" type="submit" title="Créer mon compte et continuer">Créer mon compte et continuer</button>
										</div>
									</div>
								</div>
							</form>

							<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
								<h3 class="multisteps-form__title">Lier mes comptes externes</h3>
								<div class="multisteps-form__content">
									<p>Pour accéder aux fonctionnalités les plus intéressantes du site (banque en ligne, achat de biens immobiliers...) nous devons lier votre compte à vos comptes Steam et Discord. Cette action est unique et ne vous sera plus redemandée.</p>
									<details>
										<summary>Plus d'informations sur la gestion de vos données</summary>
										<p><strong>Pourquoi lier mon compte Steam ?</strong> En liant votre compte, nous récupérons deux informations: votre SteamID, et votre image de profil. Votre SteamID sert à faire le lien entre votre compte sur le site internet et votre personnage en jeu. Cela nous permet de savoir à qui appartient tel compte bancaire par exemple. Et votre image de profil sera utilisée sur le site, dans vos messages sur le forum notamment.</p>

										<p><strong>Pourquoi lier mon compte Discord ?</strong> C'est ce lien qui nous permet de gérer une sorte de "Whitelist", sans que vous ayez d'entretien ou de candidature à faire. Le robot Discord "Rhiven BOT" vous permettra d'accéder au reste du serveur Discord automatiquement après la création de votre compte sur le site.</p>
									</details>
									<div class="row">
										<div class="col-12 col-md-6 mt-4">
											<div class="card h-100 shadow-sm">
												<div class="card-body">
													<h5 class="card-title">Discord</h5>
													<p class="card-text">Pour accéder au reste du serveur Discord</p>
													<button id="discord-btn" class="btn btn-primary" type="button" title="Lier mon compte Discord">Lier mon compte</button>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-6 mt-4">
											<div class="card h-100 shadow-sm">
												<div class="card-body">
													<h5 class="card-title">Steam</h5>
													<p class="card-text">Pour utiliser les fonctionnalités du site Internet</p>
													<button id="steam-btn" class="btn btn-primary" type="button" title="Lier mon compte Steam">Lier mon compte</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="button-row d-flex mt-4 col-12">
											<button class="btn btn-secondary js-btn-prev" type="button" title="Précédent">Précédent</button>
											<button class="btn btn-primary ml-auto js-btn-next" type="button" title="Suivant">Suivant</button>
										</div>
									</div>
								</div>
							</div>

							<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
								<h3 class="multisteps-form__title">C'est terminé !</h3>
								<div class="multisteps-form__content">
									<div class="form-row mt-4">
										<p>Il ne vous reste plus qu'une seule chose à faire: profiter de nos serveurs ! Bon jeu :)</p>
									</div>
									<div class="button-row d-flex mt-4">
										<button class="btn btn-secondary js-btn-prev" type="button" title="Précédent">Précédent</button>
										<a class="btn btn-success ml-auto" href="{{ route('home') }}" title="Accéder au site">Accéder au site</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="registration-success" class="toast" data-delay="15000" style="position: fixed; top: 4%; right: 3%;">
	<div class="toast-header">
		<img src="{{ asset('uploads/Logo_Rhiven_Transparent.png') }}" class="rounded mr-2" alt="Logo du site Rhiven" style="max-height: 25px;;">
		<strong class="mr-auto">Rhiven</strong>
		<small>À l'instant</small>
		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="toast-body">
		Votre compte a bien été créé !
	</div>
</div>

@endsection

@section('extra-js')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="{{ asset('js/register.js') }}" type="text/javascript"></script>
@endsection