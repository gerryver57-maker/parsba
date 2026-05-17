document.addEventListener('DOMContentLoaded', function() {
      // 1. Dapatkan elemen tombol dan sidebar
      const sidebarToggler = document.getElementById('headerCollapse');
      const sidebar = document.getElementById('sidebar'); // Pastikan sidebar punya ID ini

      // 2. Tambahkan event listener
      if (sidebarToggler && sidebar) {
        sidebarToggler.addEventListener('click', function(e) {
          e.preventDefault(); // Menghentikan perilaku default link
          sidebar.classList.toggle('show'); // Toggle class 'show'
        });
      }

      // 3. Handle Livewire navigation (jika menggunakan Livewire)
      document.addEventListener('livewire:navigated', function() {
        if (sidebar) {
          sidebar.classList.remove('show'); // Tutup sidebar setelah navigasi
        }
      });
    });
 