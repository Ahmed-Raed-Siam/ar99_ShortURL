<!doctype html>
<!--
	24 News by FreeHTML5.co
	Twitter: https://twitter.com/fh5co
	Facebook: https://fb.com/fh5co
	URL: https://freehtml5.co
-->
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('page-title') </title>
    <link href='{{ asset('css/media_query.css') }}' rel="stylesheet" type="text/css"/>
    <link href='{{ asset('css/bootstrap.css') }}' rel="stylesheet" type="text/css"/>
    <link href='{{ asset('css/font-awesome/css/font-awesome.css') }}' rel="stylesheet" type="text/css"/>
    <link href='{{ asset('css/animate.css') }}' rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href='{{ asset('css/owl.carousel.css') }}' rel="stylesheet" type="text/css"/>
    <link href='{{ asset('css/owl.theme.default.css') }}' rel="stylesheet" type="text/css"/>
    <!-- Bootstrap CSS -->
    <link href='{{ asset('css/style_1.css') }}' rel="stylesheet" type="text/css"/>
    <!-- Modernizr JS -->
    <script src='{{ asset("js/modernizr-3.5.0.min.js") }}'></script>
    @yield('cssCode')
    @yield('jsCode')
</head>

{{--<body class="{{ trim($__env->yieldContent('page-title')) === 'single' ? 'single' : '' }}">--}}
<body class=" {{ \Illuminate\Support\Facades\Request::is('single') ? 'single' : '' }} ">
{{--@if ( trim($__env->yieldContent('page-title')) )--}}
{{--    <h1>@yield('page-title')</h1>--}}
{{--@endif--}}

@include('frontSite.layout.header')
@include('frontSite.layout.menu')
@yield('content')

@include('frontSite.layout.footer')

{{--NEW--}}
<script src='{{ asset("js/jquery.min.js") }}'></script>
<script src='{{ asset("js/owl.carousel.min.js") }}'></script>

<script src='{{ asset("js/tether.min.js") }}'></script>
<script src={{ asset("js/bootstrap.js") }}></script>
<!-- Waypoints -->
<script src='{{ asset("js/jquery.waypoints.min.js") }}'></script>
<!-- Parallax -->
<script src='{{ asset("js/jquery.stellar.min.js") }}'></script>
<!-- Main -->
<script src='{{ asset("js/main.js") }}'></script>
@if( \Illuminate\Support\Facades\Request::is('single') )
    <script>if (!navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)){$(window).stellar();}</script>
@endif
</body>
</html>
