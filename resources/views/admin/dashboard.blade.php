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
  <style>
    body {
      font-size: 0.875rem;
    }

    .feather {
      width: 16px;
      height: 16px;
      vertical-align: text-bottom;
    }

    /*
    * Sidebar
    */

    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
      /* Behind the navbar */
      padding: 48px 0 0;
      /* Height of navbar */
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767.98px) {
      .sidebar {
        top: 5rem;
      }
    }

    .sidebar-sticky {
      position: relative;
      top: 0;
      height: calc(100vh - 48px);
      padding-top: 0.5rem;
      overflow-x: hidden;
      overflow-y: auto;
      /* Scrollable contents if viewport is shorter than content. */
    }

    @supports ((position: -webkit-sticky) or (position: sticky)) {
      .sidebar-sticky {
        position: -webkit-sticky;
        position: sticky;
      }
    }

    .sidebar .nav-link {
      font-weight: 500;
      color: #333;
    }

    .sidebar .nav-link .feather {
      margin-right: 4px;
      color: #999;
    }

    .sidebar .nav-link.router-link-active,
    .sidebar .nav-link.router-link-exact-active,
    .sidebar .nav-link.active,
    .sidebar .nav-link.active .feather {
      color: #007bff;
    }

    .sidebar .nav-link:hover .feather {
      color: inherit;
    }

    .sidebar-heading {
      font-size: 0.75rem;
      text-transform: uppercase;
    }

    /*
    * Navbar
    */

    .navbar-brand {
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
      font-size: 1rem;
      background-color: rgba(0, 0, 0, 0.25);
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.25);
    }

    .navbar .navbar-toggler {
      top: 0.25rem;
      right: 1rem;
    }

    .navbar .form-control {
      padding: 0.75rem 1rem;
      border-width: 0;
      border-radius: 0;
    }

    .form-control-dark {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.1);
      border-color: rgba(255, 255, 255, 0.1);
    }

    .form-control-dark:focus {
      border-color: transparent;
      box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
    }

    .invisible {
      height: 0px;
    }


    /*
    * Quill
    */
    .ql-editor {
      position: absolute;
      width: 100%;
    }

    .ql-toolbar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
    }

    .ql-snow .ql-editor img {
      max-height: 15em;
    }

    .ql-clipboard {
      position: fixed !important;
      left: 50% !important;
      top: 50% !important;
      opacity: 0;
    }
  </style>
</head>

<body>
  <div id="app">
    <Homepage :user-name='@json(auth()->user()->name)' :user-id='@json(auth()->user()->id)' :collection="{{ $collection }}"></Homepage>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    feather.replace();
  </script>
</body>

</html>