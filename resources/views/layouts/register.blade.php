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
                    <div class="logo"><a href="login.html"><img src="{{ asset('assets/Img/logo.png') }}" alt="logo"></a></div>
                </div>
            </nav>
        </header>
        
        <!-- Sidebar and Content -->
        <div class="topBanner"></div>


        <section class="wrapper">
            <div class="content active">
                <div class="container">
                    <br>
                    <div class="topbar">
                    <h2 class="h2">@yield('page-title', 'Vendor Registration')</h2>
                        <div class="btnWrap">
                            <nav style="--bs-breadcrumb-divider: '|';" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Vendor Registration</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <br>
                    @yield('content')
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
        new DataTable('#example');
        new DataTable('#example1');
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
        
        
    
    
    
</script>

</body>
</html>
