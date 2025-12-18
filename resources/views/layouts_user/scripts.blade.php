<!-- *************
    ************ JavaScript Files *************
************* -->
<!-- Required jQuery first, then Bootstrap Bundle JS -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>

<!-- *************
    ************ Vendor Js Files *************
************* -->

<!-- Overlay Scroll JS -->
<script src="{{ asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('assets/vendor/overlay-scroll/custom-scrollbar.js') }}"></script>

<!-- Apex Charts -->
<script src="{{ asset('assets/vendor/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/patients.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/treatment.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/available-beds.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/earnings.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/gender-age.js') }}"></script>
<script src="{{ asset('assets/vendor/apex/custom/home/claims.js') }}"></script>

<!-- Custom JS files -->
<script src="{{ asset('assets/js/custom.js') }}"></script>

@yield('scripts')