<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Survey Enevna</title>

    <!-- Styles -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            font-family: 'Cairo', sans-serif;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            flex: 1;
            background-image: url('https://img.freepik.com/free-vector/white-background-with-blue-tech-hexagon_1017-19366.jpg?t=st=1741197276~exp=1741200876~hmac=f785d6977de1e4a06400a9ec09d1aeb31c19019c473fc94a8f81dfb44caa60d6&w=996');
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .topBanner {
            width: 100%;
            height: 141px;
            background-image: url('/assets/Img/top-bg.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin-bottom: 2px;
        }

        .footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .jumbotron {
            width: 50%;
            margin: auto;
            background: linear-gradient(183deg, rgba(21, 106, 139, 1) 33%, rgba(37, 184, 241, 1) 100%);
            border-radius: 10px;
            padding: 50px;
        }

        .form-group label, .form-check-label {
            color: #fff;
        }

        @media (max-width: 993px) {
            .jumbotron {
                width: 100% !important;
            }
        }

        .btn-center {
            text-align: center;
        }

        .vr {
            text-align: center;
            color: #fff;
            margin-top: 25px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main>
        <header>
            <div class="topline"></div>
            <div class="topBanner"></div>
        </header>

        <section class="wrapper">
            @yield('content')
        </section>
    </main>

    <footer class="footer">
        <h6>survey enevna</h6>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
