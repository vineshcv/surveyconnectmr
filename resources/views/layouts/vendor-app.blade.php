<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Vendor Dashboard')</title>

    <!-- Fonts and Styles -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                <li>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>{{ session('vendor_name') }}</span>
                                            <img src="{{ asset('assets/Img/abbott.png') }}" alt="user">
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('vendor.logout') }}">
                                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        
        <!-- Sidebar and Content -->
        <div class="topBanner"></div>
        <section class="wrapper">
            <nav class="sidebar active">
                <ul class="list-unstyled">
                    <li><a href="{{ route('vendor.dashboard') }}" class="link"><i class="fa-solid fa-grip"></i> <span>Dashboard</span></a></li>
                </ul>
            </nav>

            <div class="content active">
                <div class="container-fluid">
                    <div class="bg-wraper fullWrap">
                        <div class="topbar">
                            <h2 class="h2">@yield('page-title', 'Vendor Dashboard')</h2>
                            <div class="btnWrap">
                                <nav style="--bs-breadcrumb-divider: '|';" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ Session::get('success') }}</div>
                        @endif

                        <div class="wrap">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <h6>survey enevna</h6>
        </footer>
    </div>
    
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/dataTables.js') }}"></script>
<script src="{{ asset('assets/dist/js/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@yield('scripts')

<script>
    $(document).ready(function () {
        $(".sidebarCollapse").on("click", function () {
            $(".sidebar").toggleClass("active");
            $(".content").toggleClass("active");
            $(this).toggleClass("active");
        });
        $('.select2').select2();
    });
</script>

</body>
</html>

