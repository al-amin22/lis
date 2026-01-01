<!-- Sidebar wrapper starts -->
<nav id="sidebar" class="sidebar-wrapper">
    <!-- Sidebar profile starts -->
    <div class="sidebar-profile">
        <img
            src="{{ asset('file/1.webp') }}"
            class="img-shadow img-3x me-3 rounded-5"
            alt="Hospital Admin Templates" />
        <div class="m-0">
            <h5 class="mb-1 profile-name text-nowrap text-truncate">
                Lab RS. Baiturahim
                <p class="mb-1 profile-name text-nowrap text-truncate">Jambi</p>
            </h5>
            <p class="m-0 small profile-name text-nowrap text-truncate">
                Analis Lab
            </p>
        </div>
    </div>
    <!-- Sidebar profile ends -->

    <!-- Sidebar menu starts -->
    <div class="sidebarMenuScroll">
        <ul class="sidebar-menu">
            <li class="active current-page">
                <a href="{{ route('user.index') }}">
                    <i class="ri-home-6-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ri-user-3-line"></i>
                    <span class="menu-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <!-- Sidebar menu ends -->

    <!-- Sidebar contact starts -->
    <div class="sidebar-contact">
        <p class="fw-light mb-1 text-nowrap text-truncate">
            Emergency Contact
        </p>
        <h5 class="m-0 lh-1 text-nowrap text-truncate">081367969293</h5>
        <i class="ri-phone-line"></i>
    </div>
    <!-- Sidebar contact ends -->
</nav>
<!-- Sidebar wrapper ends -->
