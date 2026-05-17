
        // Aktifkan modal edit ketika tombol edit diklik
        document.querySelector('.edit-btn').addEventListener('click', function() {
            var editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            editModal.show();
        });
        
        // Format nomor HP otomatis
        document.getElementById('phone').addEventListener('input', function(e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
        });