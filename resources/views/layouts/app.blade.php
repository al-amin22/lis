<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard laboratorium Information System</title>

    <!-- Meta -->
    <meta
        name="description"
        content="Marketplace for Bootstrap Admin Dashboards" />
    <meta property="og:title" content="Admin Templates - Dashboard Templates" />
    <meta
        property="og:description"
        content="Marketplace for Bootstrap Admin Dashboards" />
    <meta property="og:type" content="Website" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" />

    <!-- *************
		************ CSS Files *************
	************* -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/remix/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}" />

    <!-- *************
		************ Vendor Css Files *************
	************ -->

    <!-- Scrollbar CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css') }}" />
</head>

<body>
    <!-- Loading starts -->


    <!-- Loading ends -->

    <!-- Page wrapper starts -->
    <div class="page-wrapper">
        @include('layouts.header')

        <!-- Main container starts -->
        <div class="main-container">
            @include('layouts.sidebar')

            <!-- App container starts -->
            <div class="app-container">
                @yield('content')
            </div>
            <!-- App container ends -->
        </div>
        <!-- Main container ends -->
    </div>
    <!-- Page wrapper ends -->

    @include('layouts.scripts')
</body>

</html>
