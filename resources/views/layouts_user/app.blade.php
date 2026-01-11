<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Arvindo LIS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Meta -->
    <meta
        name="description"
        content="Marketplace for Bootstrap Admin Dashboards" />
    <meta property="og:title" content="Admin Templates - Dashboard Templates" />
    <meta
        property="og:description"
        content="Marketplace for Bootstrap Admin Dashboards" />
    <meta property="og:type" content="Website" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

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
    <div>
        <div class="spin-wrapper">
            <div class="spin">
                <div class="inner"></div>
            </div>
            <div class="spin">
                <div class="inner"></div>
            </div>
            <div class="spin">
                <div class="inner"></div>
            </div>
            <div class="spin">
                <div class="inner"></div>
            </div>
            <div class="spin">
                <div class="inner"></div>
            </div>
            <div class="spin">
                <div class="inner"></div>
            </div>
        </div>
    </div>
    <!-- Loading ends -->

    <!-- Page wrapper starts -->
    <div class="page-wrapper">
        @include('layouts_user.header')

        <!-- Main container starts -->
        <div class="main-container">
            @include('layouts_user.sidebar')

            <!-- App container starts -->
            <div class="app-container">
                @yield('content')
            </div>
            <!-- App container ends -->
        </div>
        <!-- Main container ends -->
    </div>
    <!-- Page wrapper ends -->

    @include('layouts_user.scripts')
</body>

</html>
