document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.btn-edit');
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const logoInput = document.getElementById('siteLogo');
    const faviconInput = document.getElementById('favicon');
    const logoPreview = document.getElementById('logoPreview');
    const faviconPreview = document.getElementById('faviconPreview');

    // === VALIDASI FILE UPLOAD ===
    const formIdentity = document.querySelector('form[action*="save-identity"]');
    const maxLogoSize = 2 * 1024 * 1024; // 2MB
    const maxFaviconSize = 1 * 1024 * 1024; // 1MB
    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

    formIdentity.addEventListener('submit', function (e) {

        const logo = document.getElementById('siteLogo').files[0];
        const favicon = document.getElementById('favicon').files[0];

        // --- Cek LOGO ---
        if (logo) {
            if (!allowedTypes.includes(logo.type)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Format Logo Tidak Valid',
                    text: 'Gunakan gambar PNG, JPG, JPEG, atau WEBP.'
                });
                return;
            }

            if (logo.size > maxLogoSize) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Logo Terlalu Besar',
                    text: 'Ukuran maksimal 2MB.'
                });
                return;
            }
        }

        // --- Cek FAVICON ---
        if (favicon) {
            if (!allowedTypes.includes(favicon.type)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Format Favicon Tidak Valid',
                    text: 'Gunakan gambar PNG, JPG, JPEG, atau WEBP.'
                });
                return;
            }

            if (favicon.size > maxFaviconSize) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Favicon Terlalu Besar',
                    text: 'Ukuran maksimal 1MB.'
                });
                return;
            }
        }
    });

    function validateAndPreview(input, preview, maxSize, label) {
        const file = input.files[0];
        const allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

        if (!file) return;

        // format salah
        if (!allowed.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: `Format ${label} Tidak Valid`,
                text: 'Gunakan PNG/JPG/JPEG/WEBP.'
            });
            input.value = "";
            preview.style.display = "none";
            return;
        }

        // ukuran terlalu besar
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: `${label} Terlalu Besar`,
                text: `Ukuran maksimal ${(maxSize/1024/1024)}MB.`
            });
            input.value = "";
            preview.style.display = "none";
            return;
        }

        // valid → tampilkan preview
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }

    // Pasang ke event onchange
    logoInput.addEventListener('change', () => {
        validateAndPreview(logoInput, logoPreview, 2 * 1024 * 1024, "Logo");
    });

    faviconInput.addEventListener('change', () => {
        validateAndPreview(faviconInput, faviconPreview, 1 * 1024 * 1024, "Favicon");
    });

    logoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            logoPreview.src = URL.createObjectURL(file);
            logoPreview.style.display = 'block';
        } else {
            logoPreview.src = '';
            logoPreview.style.display = 'none';
        }
    });

    faviconInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            faviconPreview.src = URL.createObjectURL(file);
            faviconPreview.style.display = 'block';
        } else {
            faviconPreview.src = '';
            faviconPreview.style.display = 'none';
        }
    });

    document.querySelectorAll('.btn-delete-logo').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.getAttribute('href');
            Swal.fire({
                title: 'Hapus Logo?',
                text: "Logo akan dihapus dari website.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    document.querySelectorAll('.btn-delete-favicon').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.getAttribute('href');
            Swal.fire({
                title: 'Hapus Favicon?',
                text: "Favicon akan dihapus dari website.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
    
    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            document.getElementById('formEdit').action = `<?= base_url('admin/web-setting/navbar/update/') ?>${id}`;
            document.getElementById('editTitle').value = btn.getAttribute('data-title');
            document.getElementById('editUrl').value = btn.getAttribute('data-url');
            document.getElementById('editOrder').value = btn.getAttribute('data-order');
        });
    });

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // cegah default href

            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href; // redirect ke controller delete
                }
            });
        });
    });
});

document.getElementById('maintenanceMode').addEventListener('change', function () {
    const isActive = this.checked ? 1 : 0;

    fetch(`${BASE_URL}/maintenance-toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                active: isActive,
                [CSRF_NAME]: CSRF_HASH
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Status maintenance mode diperbarui!'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat update.'
                });
            }
        })
        .catch(err => console.error(err));
});
