<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

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
                                @auth
                                    <li>
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span>{{ Auth::user()->name }}</span>
                                                <img src="{{ asset('assets/Img/abbott.png') }}" alt="user">
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a href="#" class="dropdown-item">Profile</a></li>
                                                <li><a href="#" class="dropdown-item">Settings</a></li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endauth
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
                    <!-- <li><a href="{{ url('/dashboard') }}" class="link"><i class="fa-solid fa-grip"></i> <span>Dashboard</span></a></li>
                    
                    
                    <li><a href="{{ url('/project') }}" class="link"><i class="fa-solid fa-cube"></i><span>Project</span></a></li> -->
                    <li><a href="{{ url('/invoices') }}" class="link"><i class="fa fa-file-invoice "></i><span>Invoice</span></a></li>
                    <li><a href="{{ url('/questions') }}" class="link"><i class="fa fa-question-circle"></i><span>Question</span></a></li>
                    <li><a href="{{ url('/vendors') }}" class="link"><i class="fa fa-store"></i> <span>Vendor</span></a></li>
                    @can('view-vendor-registrations')
                        <li><a href="{{ route('admin.vendor-registrations.index') }}" class="link"><i class="fa fa-user-plus"></i><span>Vendor Registration</span></a></li>
                    @endcan
                    <li><a href="{{ url('/clients') }}" class="link"><i class="fa fa-address-book"></i> <span>Client</span></a></li>
                    <li><a href="{{ url('/users') }}" class="link"><i class="fa fa-users"></i><span>User</span></a></li>
                    <li><a href="{{ url('/projects') }}" class="link"><i class="fa fa-envelope"></i><span>Projects</span></a></li>
                    <li><a href="{{ url('/roles') }}" class="link"><i class="fa fa-user-shield"></i><span>Role</span></a></li>
                </ul>
            </nav>

            <div class="content active">
                <div class="container-fluid">
                    <div class="bg-wraper fullWrap">
                        <div class="topbar">
                            {{-- 
                                Page Title: Automatically set by SetPageTitle middleware
                                To override in a view, use: @section('page-title', 'Custom Title')
                                Examples:
                                - Project List: "Project List"
                                - Edit Project: "Edit Project - Project Name"
                                - Create Client: "Create Client"
                            --}}
                            <h2 class="h2">@yield('page-title', $pageTitle ?? 'Dashboard')</h2>
                            <div class="btnWrap">
                                <nav style="--bs-breadcrumb-divider: '|';" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/home"><i class="fa-solid fa-house"></i></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        @if (Session::has('success'))
                            <!-- <div class="alert alert-success text-center">{{ Session::get('success') }}</div> -->
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

    // this is for user crud
        $(function() {
            $("#createModal").appendTo("body");
        });
        $(function() {
            $("#editModal").appendTo("body");
        });
        $(function() {
            $("#showModal").appendTo("body");
        });
    // end user crud

    // this is for client crud
        $(function() {
            $("#clientCreateModal").appendTo("body");
        });
        $(function() {
            $("#clientEditModal").appendTo("body");
        });
        $(function() {
            $("#clientShowModal").appendTo("body");
        });
    // end client crud

    // this is for vendor crud
        $(function() {
            $("#vendorShowModal").appendTo("body");
        });
        $(function() {
            $("#vendorEditModal").appendTo("body");
        });
        $(function() {
            $("#vendorCreateModal").appendTo("body");
        });

    // this is for role crud
        $(function() {
            $("#roleShowModal").appendTo("body");
        });
        $(function() {
            $("#roleEditModal").appendTo("body");
        });
        $(function() {
            $("#roleCreateModal").appendTo("body");
        });
    // end role crud   

    // this is for invoice crud
        $(function() {
            $("#invoiceCreateModal").appendTo("body");
        });
        $(function() {
            $("#invoiceEditModal").appendTo("body");
        });
        $(function() {
            $("#invoiceShowModal").appendTo("body");
        });
    // end role crud    

    // this is for project
    
        $(function() {
            $("#formModal").appendTo("body");
        });
        
        
    
    
    
</script>

</body>
</html>
