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
            </h5>
            <p class="mb-1 profile-name text-nowrap text-truncate">
                Jambi
            </p>
            <p class="m-0 small profile-name text-nowrap text-truncate">
                Analis Lab
            </p>
        </div>
    </div>
    <!-- Sidebar profile ends -->

    <!-- Sidebar menu starts -->
    <div class="sidebarMenuScroll">
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="{{ request()->routeIs('pasien.index') ? 'active current-page' : '' }}">
                <a href="{{ route('pasien.index') }}" class="d-flex align-items-center">
                    <i class="ri-home-6-line me-3"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Master Data (Dropdown) -->
            <li>
                <a href="#submenu-master"
                    data-bs-toggle="collapse"
                    aria-expanded="false"
                    class="d-flex align-items-center collapsed">
                    <i class="ri-database-2-line me-3"></i>
                    <span class="menu-text">Master Data</span>
                    <i class="ri-arrow-down-s-line ms-auto"></i>
                </a>

                <ul class="sidebar-menu collapse" id="submenu-master">
                    <li class="{{ request()->routeIs('pasien.dokter.index') ? 'active' : '' }}">
                        <a href="{{ route('pasien.dokter.index') }}" class="d-flex align-items-center">
                            <i class="ri-user-2-line me-3"></i>
                            <span class="menu-text">Data Dokter</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pasien.pemeriksa.index') ? 'active' : '' }}">
                        <a href="{{ route('pasien.pemeriksa.index') }}" class="d-flex align-items-center">
                            <i class="ri-stethoscope-line me-3"></i>
                            <span class="menu-text">Data Analis</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pasien.ruangan.index') ? 'active' : '' }}">
                        <a href="{{ route('pasien.ruangan.index') }}" class="d-flex align-items-center">
                            <i class="ri-hospital-line me-3"></i>
                            <span class="menu-text">Data Ruangan</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pasien.kelas.index') ? 'active' : '' }}">
                        <a href="{{ route('pasien.kelas.index') }}" class="d-flex align-items-center">
                            <i class="ri-home-line me-3"></i>
                            <span class="menu-text">Data Kelas</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Tambah Data Pasien -->
            <li class="{{ request()->routeIs('pasien.create') ? 'active' : '' }}">
                <a href="{{ route('pasien.create') }}" class="d-flex align-items-center">
                    <i class="ri-heart-pulse-line me-3"></i>
                    <span class="menu-text">Tambah Data Pasien</span>
                </a>
            </li>

            <!-- Jenis Pemeriksaan -->
            <li class="{{ request()->routeIs('pasien.index.jenis.pemeriksaan') ? 'active' : '' }}">
                <a href="{{ route('pasien.index.jenis.pemeriksaan') }}" class="d-flex align-items-center">
                    <i class="ri-file-list-3-line me-3"></i>
                    <span class="menu-text">Jenis Pemeriksaan</span>
                </a>
            </li>

            <!-- Data Pemeriksaan -->
            <li class="{{ request()->routeIs('pasien.index.data.pemeriksaan') ? 'active' : '' }}">
                <a href="{{ route('pasien.index.data.pemeriksaan') }}" class="d-flex align-items-center">
                    <i class="ri-database-2-line me-3"></i>
                    <span class="menu-text">Data Pemeriksaan</span>
                </a>
            </li>

            <!-- Spacer (optional) -->
            <li class="sidebar-spacer"></li>

            <!-- Logout -->
            <li>
                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="d-flex align-items-center">
                    <i class="ri-user-3-line me-3"></i>
                    <span class="menu-text">Logout</span>
                </a>
                <form
                    id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-none">
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

<script>
    // Script untuk mengatur state dropdown berdasarkan halaman aktif
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const masterDataRoutes = [
            '/pasien/dokter',
            '/pasien/pemeriksa',
            '/pasien/ruangan',
            '/pasien/kelas'
        ];

        const isMasterDataPage = masterDataRoutes.some(route => currentPath.includes(route));

        if (isMasterDataPage) {
            const masterDataLink = document.querySelector('a[href="#submenu-master"]');
            const masterDataMenu = document.getElementById('submenu-master');

            if (masterDataLink && masterDataMenu) {
                // Tambah class 'active' ke parent li
                masterDataLink.parentElement.classList.add('active');

                // Buka dropdown
                masterDataLink.classList.remove('collapsed');
                masterDataLink.setAttribute('aria-expanded', 'true');
                masterDataMenu.classList.add('show');
            }
        }
    });
</script>
