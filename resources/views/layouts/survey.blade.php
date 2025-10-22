<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Survey')</title>

    <!-- Fonts and Styles -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .survey-content {
            min-height: 60vh;
            padding: 2rem 0;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <!-- Header -->
            <header>
                <div class="topline"></div>
                <nav class="navbar navbar-expand">
                    <div class="container-fluid">
                        <ul class="navbar-nav mb-2 mb-md-0">
                            <li class="nav-item">
                                <button type="button" class="btn navbar-btn sidebarCollapse">
                                    <i class="fa-solid fa-bars"></i>
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="logo">
                            <img src="{{ asset('assets/Img/logo.png') }}" alt="logo">
                        </div>
                        <div class="collapse navbar-collapse" id="navbarCollapse">
                            <div class="drop-menu ms-auto">
                                <ul class="navbar-nav sm-icons">
                                    <!-- Survey pages don't need user dropdown -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Survey Content -->
            <section class="survey-content">
                <div class="container">
                    @yield('content')
                </div>
            </section>

            <!-- Footer -->
            <footer>
                <h6>survey enevna</h6>
            </footer>
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>