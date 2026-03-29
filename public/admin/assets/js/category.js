document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('categoryModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('categoryForm');
    const tbody = document.getElementById('categoryTableBody');

    const iconGrid = document.getElementById('iconGrid');
    const categoryIconInput = document.getElementById('categoryIcon');
    const iconPreview = document.getElementById('iconPreview');
    const previewIcon = document.getElementById('previewIcon');

    // ✅ Atur default opsi Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };

    // Reset otomatis setiap kali modal ditutup
    modalEl.addEventListener('hidden.bs.modal', () => {
        form.reset();
        form.categoryId.value = '';
    });

    // Tombol Tambah Kategori
    document.getElementById('btnAdd').addEventListener('click', () => {
        form.reset();
        form.categoryId.value = '';
        categoryIconInput.value = '';
        iconPreview.classList.add('d-none');
        previewIcon.className = '';
        document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
        document.querySelector('.modal-title').textContent = 'Tambah Kategori';
        modal.show();
    });

    // Submit form (Tambah/Edit)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const res = await fetch(base_url + '/admin/category/save', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.status === 'success') {
                tbody.innerHTML = data.html;
                modal.hide();
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        } catch (error) {
            console.error(error);
            toastr.error('Terjadi kesalahan saat menyimpan data.');
        }
    });

    // Edit & Hapus
    tbody.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.btnEdit');
        const delBtn = e.target.closest('.btnDelete');

        // EDIT
        if (editBtn) {
            form.categoryId.value = editBtn.dataset.id;
            form.categoryName.value = editBtn.dataset.name;

            const iconClass = editBtn.dataset.icon || '';
            categoryIconInput.value = iconClass;

            document.querySelectorAll('.icon-btn').forEach(b => {
                if (b.querySelector('i').classList.contains(iconClass)) {
                    b.classList.add('active', 'btn-primary');
                } else {
                    b.classList.remove('active', 'btn-primary');
                }
            });

            if (iconClass) {
                previewIcon.className = `fas ${iconClass} fa-3x`;
                iconPreview.classList.remove('d-none');
            } else {
                iconPreview.classList.add('d-none');
            }

            document.querySelector('.modal-title').textContent = 'Edit Kategori';
            modal.show();
        }

        // DELETE
        // DELETE (pakai SweetAlert2)
        if (delBtn) {
            Swal.fire({
                title: "Hapus kategori ini?",
                text: "Data akan dihapus secara permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', delBtn.dataset.id);

                    fetch(base_url + '/admin/category/delete', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                tbody.innerHTML = data.html;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus data.'
                            });
                        });
                }
            });
        }
    });

    // List icon yang ditampilkan
    const icons = [{
            name: 'Accessories',
            class: 'fa-ring',
            color: 'text-primary'
        },
        {
            name: 'ATK',
            class: 'fa-pen',
            color: 'text-primary'
        },
        {
            name: 'Cetak',
            class: 'fa-print',
            color: 'text-primary'
        },
        {
            name: 'Design',
            class: 'fa-paint-brush',
            color: 'text-primary'
        },
        {
            name: 'Elektronik',
            class: 'fa-bolt',
            color: 'text-primary'
        },
        {
            name: 'Sablon',
            class: 'fa-tshirt',
            color: 'text-primary'
        },
        {
            name: 'Undangan',
            class: 'fa-envelope',
            color: 'text-primary'
        }
    ];

    // Render icon buttons
    icons.forEach(icon => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary d-flex flex-column align-items-center justify-content-center p-3 icon-btn';
        btn.style.width = '80px';
        btn.style.height = '80px';
        btn.innerHTML = `<i class="fas ${icon.class} fa-2x ${icon.color}"></i><small class="mt-2">${icon.name}</small>`;

        btn.addEventListener('click', () => {
            document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
            btn.classList.add('active', 'btn-primary');

            categoryIconInput.value = icon.class;
            previewIcon.className = `fas ${icon.class} fa-3x ${icon.color}`;
            iconPreview.classList.remove('d-none');
        });

        iconGrid.appendChild(btn);
    });
});
