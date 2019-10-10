<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Middle Earth Export') - Middle Earth</title>
    <!-- style -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
     <script src="https://www.paypal.com/sdk/js?client-id=AQlmHEGWwWyKEgnna8faQSXC2-VEnp50nZOdri6eTnr7Y2hD2tno2r_AsdyrR3ourxmDIgYA8-lCjQfG ">

    </script>



</head>
<body>
    <div id="app" class="{{ route_class() }}-page">
        @include('layouts._header')
        <div class="container">
            @yield('content')

        </div>
        @include('layouts._footer')
    </div>
    <!-- JS script -->
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scriptsAfterJs')
</body>
</html>
