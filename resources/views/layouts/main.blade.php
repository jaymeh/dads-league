<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- CSRF Token -->
    	<meta name="csrf-token" content="{{ csrf_token() }}">    

        <!-- Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">

        <!-- Styles -->
    	
    	<!-- Scripts -->
    	<script src="{{ asset('js/app.js') }}" defer></script>

    </head>

    <body>
    	<div id="app">
	    	<v-app id="dads-league">
		    	@yield('content')
		    </v-app>
		</div>
    </body>
</html>