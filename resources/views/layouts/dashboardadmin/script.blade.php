<script src="{{asset('dashboard/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/sidebarmenu.js')}}"></script>
<script src="{{asset('dashboard/js/app.min.js')}}"></script>
<script src="{{asset('dashboard/libs/simplebar/dist/simplebar.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{asset('dashboard/js/dashboardpetani.js')}}"></script>
<script src="{{asset('dashboard/js/tombol.js')}}"></script>
<script src="{{asset('dashboard/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
<script>
    function initSidebar() {
      if (typeof window.initSidebarmenu === 'function') {
        window.initSidebarmenu();
      }
    }

    document.addEventListener('DOMContentLoaded', initSidebar);
    document.addEventListener('livewire:navigated', initSidebar);
  </script>