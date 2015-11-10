<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    {!! csrf_field() !!}
    <title>@yield('title') - Account Verification</title>

    <!-- CSS -->
    <link rel="stylesheet"
          href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/authy-forms.css/2.2/form.authy.min.css">
    <link rel="stylesheet" href="{{ asset('css/application.css') }}">

    @yield('css')
  </head>
  <body>
      <section id="main" class="container">
      <!-- Nav Bar -->
      <nav class="navbar navbar-default">
        <a class="navbar-brand" href="/">Account Verification</a>
        <p id="nav-links" class="navbar-text pull-right">
          <a href="{{ route('user-new') }}">Sign up</a>
        </p>
      </nav>

      @include('_messages')

      @yield('content')

    </section>

    <footer class="container">
      Made with <i class="fa fa-heart"></i> by your pals
      <a href="http://www.twilio.com">@twilio</a>
    </footer>

    <!-- JavaScript -->
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/authy-forms.js/2.2/form.authy.min.js"></script>

    @yield('javascript')
  </body>
</html>
