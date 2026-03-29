document.addEventListener('DOMContentLoaded', () => {
    const productTable = document.querySelector('#product-table');
    const limitSelect = document.querySelector('#limitSelect');
    const searchInput = document.querySelector('#searchInput');

    // === Global State ===
    let state = {
        sort: 'name',
        order: 'asc',
        page: 1,
        limit: limitSelect?.value || 20,
        search: ''
    };

    // === Debounce helper ===
    function debounce(fn, delay = 400) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn(...args), delay);
        };
    }

    // === Fetch Data ===
    function fetchData() {
        const query = new URLSearchParams(state).toString();

        fetch(`/admin/product/fetch?${query}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.text())
            .then(html => {
                // Ganti seluruh tabel (karena controller return _partial.php)
                const tableContainer = document.querySelector('#product-table');
                if (tableContainer) {
                    tableContainer.innerHTML = html;
                } else {
                    console.error('#product-table tidak ditemukan!');
                }
            })
            .catch(err => console.error('Fetch error:', err));
    }

    // === Delegasi event untuk sorting, pagination, delete ===
    productTable.addEventListener('click', e => {
        const sortLink = e.target.closest('.sort');
        const pageLink = e.target.closest('.pagination a');
        const delBtn = e.target.closest('.delete-btn');

        if (sortLink) {
            e.preventDefault();
            const sort = sortLink.dataset.sort;
            const order = sortLink.dataset.order === 'asc' ? 'desc' : 'asc';
            sortLink.dataset.order = order;
            state.sort = sort;
            state.order = order;
            state.page = 1;
            fetchData();
        }

        if (pageLink) {
            e.preventDefault();
            const url = new URL(pageLink.href);
            state.page = parseInt(url.searchParams.get('page')) || 1;
            fetchData();
        }

        if (delBtn) {
            const id = delBtn.dataset.id;
            Swal.fire({
                title: 'Yakin hapus produk ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
                }
            }).then(res => {
                if (res.isConfirmed) {
                    fetch(`/admin/product/delete/${id}`, {
                            method: 'POST',
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        })
                        .then(r => r.json())
                        .then(res => {
                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Produk berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                                fetchData();
                            } else {
                                Swal.fire('Gagal', res.message || 'Gagal menghapus produk.', 'error');
                            }
                        });
                }
            });
        }
    });

    // === Limit per halaman ===
    document.addEventListener('change', e => {
        const select = e.target.closest('#limitSelect');
        if (select) {
            state.limit = select.value;
            state.page = 1;
            fetchData();
        }
    });

    // === Live Search (AJAX) ===
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                state.search = searchInput.value.trim();
                state.page = 1;
                fetchData();
            }, 400); // debounce 0.4 detik agar tidak terlalu sering request
        });
    }
});
