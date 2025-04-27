<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('assets/img/e-vote-logo-3.png') }}" type="image/x-icon">
    {{-- <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sb-admin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://naramizaru.github.io/fa-pro/css/all.min.css">
    @stack('css')
    <title>{{ config('app.name') }} | @yield('title')</title>
</head>

<body class="min-vh-100 d-flex justify-content-center align-items-center bg-primary">
    <div id="preloader">
        <img src="{{ asset('assets/img/e-vote-logo-1.png') }}" alt="Logo Preloader">
    </div>
    @yield('content')

    @include('sweetalert::alert')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin.min.js') }}"></script>
    <script>
        window.addEventListener("load", function() {
            const preloader = document.getElementById("preloader");
            preloader.style.opacity = "0";
            setTimeout(() => {
                preloader.style.display = "none";
            }, 1000);
        });
    </script>
    @stack('js')

</body>

</html>
