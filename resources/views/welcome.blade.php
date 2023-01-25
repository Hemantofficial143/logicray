<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('/css/modal.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
@yield('modal')
@include('includes.header')
@yield('content')
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/jquery.js') }}"></script>
<script src="{{ asset('/js/jquery.validate.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.4/axios.min.js" integrity="sha512-Th6RhKVKcUO1NdowoioG5HrNgi4JzStsjpwheSR+nWcDIVO4Wv6E6D14o/46EqqDsLSca/rcMD1a3OLyPkexAw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('scripts')
</body>
</html>
