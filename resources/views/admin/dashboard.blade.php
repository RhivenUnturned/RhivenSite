<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="L'expérience RP Unturned francophone la plus poussée. Plugins, Mods, Site Internet innovant, découvrez Rhiven !">
  <meta name="author" content="Ceans">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    window.Laravel = {
      csrfToken: 'csrf_token() '
    }
  </script>
  <title>Administration | Rhiven [FR]</title>
  <link rel="icon" href="/favicon.ico" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body>
  <div id="app">
    <Homepage :user-name='@json(auth()->user()->name)' :user-id='@json(auth()->user()->id)'></Homepage>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    feather.replace()
  </script>
</body>

</html>