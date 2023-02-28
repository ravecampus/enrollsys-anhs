<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <meta name="keywords" content="au theme template">
    <title>{{env('MIX_APP_NAME')}}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset(url('css/main.css')) }}">
    
    <!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <link rel="stylesheet" type="text/css" href="{{ asset(url('/font-awesome/css/font-awesome.min.css')) }}">
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
</head>
<body class="app sidebar-mini" id="app">
@if (Auth::check())
    @php
    $user_auth_data = [
        'isLoggedin' => true,
        'user' =>  Auth::user()
    ];
    @endphp
@else
    @php
    $user_auth_data = [
        'isLoggedin' => false
    ];
    @endphp
@endif


<!-- <script src="{{ asset('vendor/jquery-3.2.1.min.js') }}"></script> -->
<script src="{{ asset(url('js/jquery-3.3.1.min.js')) }}"></script>
<script src="{{ asset(url('js/popper.min.js')) }}"></script>
<script src="{{ asset(url('js/bootstrap.min.js')) }}"></script>
<!-- <script src="{{ asset(url('js/main.js')) }}"></script> -->
<!-- Bootstrap JS-->
<script>
    window.Laravel = JSON.parse(atob('{{ base64_encode(json_encode($user_auth_data)) }}'));
</script>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset(url('js/main.js')) }}"></script>
</body>
</html>