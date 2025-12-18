<!-- App header starts -->
<div class="app-header d-flex align-items-center">
    <!-- Toggle buttons starts -->
    <div class="d-flex">
        <button class="toggle-sidebar">
            <i class="ri-menu-line"></i>
        </button>
        <button class="pin-sidebar">
            <i class="ri-menu-line"></i>
        </button>
    </div>
    <!-- Toggle buttons ends -->

    <!-- App brand starts -->
    <div class="app-brand ms-3">
        <a href="{{ url('admin/dashboard') }}" class="d-lg-block d-none">
            <img
                src="https://arvindokaryautama.com/wp-content/uploads/2020/09/Logo-Arvindo-Sementara.png"
                class="logo"
                alt="Medicare Admin Template" />
        </a>
        <a href="{{ url('admin/dashboard') }}" class="d-lg-none d-md-block">
            <img
                src="{{ asset('assets/images/logo-sm.svg') }}"
                class="logo"
                alt="Medicare Admin Template" />
        </a>
    </div>
    <!-- App brand ends -->

    <!-- App header actions starts -->
    <div class="header-actions">
        <!-- Search container starts -->
        <div class="search-container d-lg-block d-none mx-3">
            <form method="GET" action="{{ route('pasien.search') }}" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control form-control-sm me-2"
                    placeholder="Cari RM Pasien / Nama" value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-light">Search</button>
            </form>
            <i class="ri-search-line"></i>
        </div>
        <!-- Search container ends -->
        <!-- Header user settings ends -->
    </div>
    <!-- App header actions ends -->
</div>
<!-- App header ends -->
