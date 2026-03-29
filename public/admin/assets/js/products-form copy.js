document.addEventListener("DOMContentLoaded", () => {
    const ProductForm = {
        baseUrl: base_url.replace(/\/$/, ""),
        mediaModal: null,
        mediaMode: {
            type: "main",
            row: null
        },
        gallerySelected: [],

        init() {
            this.cacheDom();
            this.gallerySelected = window.gallerySelected || [];
            this.updateGalleryPreview();
            this.bindEvents();
            this.initEditors();
            this.initWarranty();
            this.loadExistingVariants();
            this.initUploadTab();
        },

        // =========================
        // CACHE DOM ELEMENTS
        // =========================
        cacheDom() {
            // Media
            this.mainDropzone = document.getElementById("mainImageDropzone");
            this.mainPreview = document.getElementById("mainImagePreview");
            this.mainInput = document.getElementById("mainImageInput");
            this.mediaModalEl = document.getElementById("mediaModal");
            this.mediaLibraryContainer = document.getElementById("mediaLibraryContainer");
            this.selectMediaBtn = document.getElementById("selectMediaBtn");

            // Gallery
            this.galleryContainer = document.getElementById("gallery-container");
            this.galleryImagesInput = document.getElementById("galleryImagesInput");
            this.selectGalleryBtn = document.getElementById("selectGalleryBtn");

            // Attributes & Variants
            this.attrContainer = document.getElementById("attribute-container");
            this.addAttrBtn = document.getElementById("add-attribute");
            this.variantsTableContainer = document.getElementById("variants-table");
            this.variantsTextarea = document.getElementById("variants");
        },

        // =========================
        // EVENT BINDING
        // =========================
        bindEvents() {
            // Media selection
            this.mainDropzone?.addEventListener("click", () => this.openMediaModal("main"));
            this.selectGalleryBtn?.addEventListener("click", () => this.openMediaModal("gallery"));
            this.selectMediaBtn?.addEventListener("click", () => this.confirmMediaSelection());

            // Attributes
            this.addAttrBtn?.addEventListener("click", () => this.addAttribute());
            this.attrContainer?.addEventListener("input", () => this.updateVariants());
            this.attrContainer?.addEventListener("click", e => {
                if (e.target.classList.contains("remove-attr")) {
                    e.target.closest(".attribute-item").remove();
                    this.updateVariants();
                }
            });

            // Variants table
            this.variantsTableContainer?.addEventListener("click", e => {
                const row = e.target.closest("tr");
                if (!row) return;
                if (e.target.classList.contains("remove-variant")) {
                    row.remove();
                    this.updateJSON();
                } else if (e.target.classList.contains("select-variant-image")) {
                    this.openMediaModal("variant", row);
                }
            });
            this.variantsTableContainer?.addEventListener("input", () => this.updateJSON());

            // Form submission
            document.querySelector("form")?.addEventListener("submit", e => this.submitForm(e));

            // =========================
            // UPLOAD MEDIA (Tab Upload Baru)
            // =========================
            const uploadInput = document.getElementById("uploadMediaInput");
            const uploadPreview = document.getElementById("uploadPreview");
            const uploadBtn = document.getElementById("uploadMediaBtn");

            // Preview file sebelum upload
            uploadInput ?.addEventListener("change", () => {
                uploadPreview.innerHTML = "";
                Array.from(uploadInput.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("rounded", "border");
                        img.style.width = "100px";
                        img.style.height = "100px";
                        img.style.objectFit = "cover";
                        uploadPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Tombol upload
            uploadBtn?.addEventListener("click", async () => {
                const files = uploadInput.files;
                if (!files.length) {
                    Swal.fire({
                        icon: "warning",
                        title: "Tidak ada file",
                        text: "Pilih minimal satu file terlebih dahulu.",
                        confirmButtonText: "Oke"
                    });
                    return;
                }

                const formData = new FormData();
                for (const f of files) formData.append("files[]", f);

                uploadBtn.disabled = true;
                uploadBtn.textContent = "Mengunggah...";

                try {
                    const res = await fetch(`${this.baseUrl}/admin/media/upload`, {
                        method: "POST",
                        body: formData
                    });

                    const data = await res.json();
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Upload berhasil!",
                            text: "File telah berhasil diunggah.",
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Kosongkan input dan preview
                        uploadInput.value = "";
                        uploadPreview.innerHTML = "";

                        // Pindah ke tab Library
                        const libraryTab = document.querySelector("#library-tab");
                        const bsTab = new bootstrap.Tab(libraryTab);
                        bsTab.show();

                        // Muat ulang media library
                        this.loadMediaLibrary(this.mediaMode.type === "gallery");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Upload gagal",
                            text: data.message || "Terjadi kesalahan saat upload file.",
                            confirmButtonText: "Tutup"
                        });
                    }
                } catch (err) {
                    console.error("Upload gagal:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Kesalahan jaringan",
                        text: "Terjadi kesalahan saat mengunggah file.",
                        confirmButtonText: "Oke"
                    });
                } finally {
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = "Upload";
                }
            });
        },

        // =========================
        // MEDIA LIBRARY
        // =========================
        async loadMediaLibrary(isGallery = false) {
            this.mediaLibraryContainer.innerHTML = `<div class="text-center py-5 text-muted">Memuat gambar...</div>`;
            try {
                const res = await fetch(`${this.baseUrl}/admin/media/list/0/50`);
                const files = await res.json();

                this.mediaLibraryContainer.innerHTML = files.map(f => `
                    <div class="col-md-2 mb-3">
                        <div class="media-item border rounded p-1" 
                            data-id="${f.id}" data-path="${f.file_path}">
                            <img src="${this.baseUrl}/${f.file_path}" 
                                alt="${f.file_name}" class="img-fluid rounded"
                                style="object-fit:cover;height:100px;width:100%;cursor:pointer;">
                        </div>
                    </div>`).join("");

                this.mediaLibraryContainer.querySelectorAll(".media-item").forEach(item => {
                    item.addEventListener("click", () => {
                        if (isGallery) item.classList.toggle("selected");
                        else {
                            this.mediaLibraryContainer.querySelectorAll(".media-item")
                                .forEach(i => i.classList.remove("selected"));
                            item.classList.add("selected");
                        }
                    });
                });
            } catch (err) {
                console.error("Gagal memuat media:", err);
                this.mediaLibraryContainer.innerHTML = `<div class="text-danger text-center py-5">Gagal memuat media.</div>`;
            }
        },

        openMediaModal(type, row = null) {
            if (!this.mediaModal) this.mediaModal = new bootstrap.Modal(this.mediaModalEl);
            this.mediaMode = {
                type,
                row
            };
            this.loadMediaLibrary(type === "gallery");
            this.mediaModal.show();
        },

        confirmMediaSelection() {
            const selected = Array.from(this.mediaLibraryContainer.querySelectorAll(".media-item.selected"));
            if (!selected.length) return;

            const selectedData = selected.map(i => ({
                id: i.dataset.id,
                path: i.dataset.path
            }));

            switch (this.mediaMode.type) {
                case "main":
                    this.setMainImage(selectedData[0]);
                    break;
                case "gallery":
                    this.addGalleryImages(selectedData);
                    break;
                case "variant":
                    this.setVariantImage(selectedData[0]);
                    break;
            }
            this.mediaModal.hide();
        },

        setMainImage(file) {
            this.mainPreview.src = `${this.baseUrl}/${file.path}`;
            this.mainPreview.style.display = "block";
            this.mainInput.value = file.id; // gunakan media_id
        },

        addGalleryImages(files) {
            files.forEach(f => {
                if (!this.gallerySelected.some(g => g.id === f.id)) this.gallerySelected.push(f);
            });
            this.updateGalleryPreview();
        },

        updateGalleryPreview() {
            this.galleryContainer.innerHTML = this.gallerySelected.map((f, i) => `
                <div class="position-relative">
                    <img src="${this.baseUrl}/${f.path}" class="img-thumbnail rounded" 
                        style="width:100px;height:100px;object-fit:cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-gallery-btn">&times;</button>
                </div>`).join("");

            this.galleryImagesInput.value = JSON.stringify(this.gallerySelected.map(f => f.id));

            this.galleryContainer.querySelectorAll(".remove-gallery-btn").forEach((btn, i) => {
                btn.addEventListener("click", () => {
                    this.gallerySelected.splice(i, 1);
                    this.updateGalleryPreview();
                });
            });
        },

        // =========================
        // ATTRIBUTES & VARIANTS
        // =========================
        addAttribute() {
            const div = document.createElement("div");
            div.classList.add("attribute-item", "mb-2", "d-flex", "gap-2");
            div.innerHTML = `
                <input type="text" class="form-control attr-name" placeholder="Nama atribut">
                <input type="text" class="form-control attr-values" placeholder="Nilai (pisahkan koma)">
                <button type="button" class="btn btn-danger btn-sm remove-attr">X</button>`;
            this.attrContainer.appendChild(div);
        },

        updateVariants() {
            const attrs = Array.from(this.attrContainer.querySelectorAll(".attribute-item"))
                .map(item => {
                    const name = item.querySelector(".attr-name").value.trim();
                    const values = item.querySelector(".attr-values").value.split(",").map(v => v.trim()).filter(Boolean);
                    return name && values.length ? {
                        name,
                        values
                    } : null;
                })
                .filter(Boolean);

            if (!attrs.length) {
                this.variantsTableContainer.innerHTML = "";
                this.variantsTextarea.value = "";
                return;
            }

            this.renderVariantTable(attrs);
        },

        cartesianProduct(arr) {
            return arr.reduce((a, b) => a.flatMap(d => b.map(e => d.concat([e]))), [
                []
            ]);
        },

        // =========================
        // 1. Load existing variants
        // =========================
        loadExistingVariants() {
            if (!window.existingVariants?.length) return;

            const attrMap = {};
            window.existingVariants.forEach(v => {
                Object.entries(v.attributes).forEach(([key, val]) => {
                    if (!attrMap[key]) attrMap[key] = new Set();
                    attrMap[key].add(val);
                });
            });

            // Render attribute fields
            Object.entries(attrMap).forEach(([name, values]) => {
                const div = document.createElement("div");
                div.classList.add("attribute-item", "mb-2", "d-flex", "gap-2");
                div.innerHTML = `
            <input type="text" class="form-control attr-name" value="${name}">
            <input type="text" class="form-control attr-values" value="${Array.from(values).join(',')}">
            <button type="button" class="btn btn-danger btn-sm remove-attr">X</button>`;
                this.attrContainer.appendChild(div);
            });

            // Build imageMap dan imageIdMap
            const attrNames = Object.keys(attrMap);
            const imageMap = {};
            const imageIdMap = {};
            window.existingVariants.forEach(v => {
                const key = attrNames.map(n => v.attributes[n]).join("|");
                if (v.image) {
                    if (v.media_id) {
                        imageIdMap[key] = v.media_id; // media_id ada
                        imageMap[key] = v.image; // path untuk preview
                    } else {
                        imageIdMap[key] = ""; // varian lama tanpa media_id
                        imageMap[key] = v.image; // path lama
                    }
                }
            });

            const attrs = Array.from(this.attrContainer.querySelectorAll(".attribute-item"))
                .map(item => {
                    const name = item.querySelector(".attr-name").value.trim();
                    const values = item.querySelector(".attr-values").value.split(",").map(v => v.trim()).filter(Boolean);
                    return name && values.length ? {
                        name,
                        values
                    } : null;
                }).filter(Boolean);

            this.renderVariantTable(attrs, imageMap, imageIdMap);

            // Isi nilai varian dan diskon
            const rows = Array.from(this.variantsTableContainer.querySelectorAll("tbody tr[data-index]"));
            window.existingVariants.forEach((v, i) => {
                const row = rows[i];
                if (!row) return;

                row.querySelector(".variant-price").value = v.price ?? 0;
                row.querySelector(".variant-sale-price").value = v.sale ?? 0;
                row.querySelector(".variant-weight").value = v.weight ?? "";
                row.querySelector(".variant-stock").value = v.stock ?? 0;

                // Gambar sudah di set saat render
                row.querySelector(".variant-image-id").value = v.media_id ?? "";

                // Diskon
                const discountRow = row.nextElementSibling?.dataset.discountRow === "true" ? row.nextElementSibling : null;
                if (discountRow) {
                    const formatForDatetimeLocal = dt => {
                        if (!dt) return "";
                        // ubah format: YYYY-MM-DDTHH:MM
                        const d = new Date(dt.replace("T", " ").replace(/-/g, "/"));
                        if (isNaN(d)) return "";
                        const pad = n => n.toString().padStart(2, "0");
                        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
                    };
                    discountRow.querySelector(".variant-discount-start").value = formatForDatetimeLocal(v.sale_start);
                    discountRow.querySelector(".variant-discount-end").value = formatForDatetimeLocal(v.sale_end);
                }
            });

            this.updateJSON();
        },

        // =========================
        // 2. Render variant table
        // =========================
        renderVariantTable(attrs, imageMap = {}, imageIdMap = {}, discountMap = {}) {
            const combos = this.cartesianProduct(attrs.map(a => a.values));
            let html = `<table class="table table-bordered"><thead><tr>`;
            attrs.forEach(a => html += `<th>${a.name}</th>`);
            html += `<th>Gambar</th><th>Regular Price</th><th>Sale Price</th><th>Weight</th><th>Stock</th><th>Aksi</th></tr></thead><tbody>`;

            combos.forEach((combo, i) => {
                const key = combo.join("|");
                const imgVal = imageMap[key] || "";
                const imgId = imageIdMap[key] || "";
                const discount = discountMap[key] || {};

                html += `<tr data-index="${i}">`;
                combo.forEach(v => html += `<td>${v}</td>`);
                html += `
        <td>
            <div class="variant-image-wrapper d-flex align-items-center gap-2">
                <img src="${imgVal ? this.baseUrl + '/' + imgVal : ''}" class="variant-img-preview rounded" 
                    style="width:45px;height:45px;object-fit:cover;${imgVal?'display:block':'display:none'}">
                <button type="button" class="btn btn-outline-primary btn-sm select-variant-image">
                    ${imgVal ? 'Ubah Gambar' : 'Pilih Gambar'}
                </button>
                <input type="hidden" class="variant-image-path" value="${imgVal}">
                <input type="hidden" class="variant-image-id" value="${imgId}">
            </div>
        </td>
        <td><input type="number" class="form-control variant-price" value="${discount.price || 0}"></td>
        <td><input type="number" class="form-control variant-sale-price" value="${discount.sale || 0}"></td>
        <td><input type="text" class="form-control variant-weight" value="${discount.weight || ''}"></td>
        <td><input type="number" class="form-control variant-stock" min="0" value="${discount.stock || 0}"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-variant">X</button></td>
        </tr>
        `;

                const colspan = attrs.length + 6;
                html += `
        <tr class="bg-light" data-discount-row="true">
            <td colspan="${colspan}">
                <div class="row g-3 px-2 py-2">
                    <div class="col-md-6">
                        <label class="form-label small mb-1">Diskon Mulai</label>
                        <input type="datetime-local" class="form-control variant-discount-start" value="${discount.sale_start || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small mb-1">Diskon Berakhir</label>
                        <input type="datetime-local" class="form-control variant-discount-end" value="${discount.sale_end || ''}">
                    </div>
                </div>
            </td>
        </tr>
        `;
            });

            this.variantsTableContainer.innerHTML = html;
            this.updateJSON();
        },

        // =========================
        // 3. Set variant image
        // =========================
        setVariantImage(file) {
            const row = this.mediaMode.row;
            const imgEl = row.querySelector(".variant-img-preview");

            imgEl.src = `${this.baseUrl}/${file.path}`;
            imgEl.style.display = "block";

            // Update hidden input
            row.querySelector(".variant-image-id").value = file.id; // media_id
            row.querySelector(".variant-image-path").value = file.path; // optional, untuk preview

            row.querySelector(".select-variant-image").textContent = "Ubah Gambar";

            this.updateJSON();
        },

        updateJSON() {
            const header = Array.from(this.variantsTableContainer.querySelectorAll("thead th"));
            const attrNames = header.slice(0, header.length - 6).map(th => th.textContent.trim());

            const rows = Array.from(this.variantsTableContainer.querySelectorAll("tbody tr[data-index]"));

            const data = rows.map(row => {
                const attrValues = Array.from(row.querySelectorAll("td")).slice(0, attrNames.length).map(td => td.textContent.trim());
                const attributes = {};
                attrNames.forEach((n, i) => attributes[n] = attrValues[i]);

                const discountRow = row.nextElementSibling?.dataset.discountRow === "true" ? row.nextElementSibling : null;

                return {
                    attributes,
                    image: row.querySelector(".variant-image-id")?.value || "", // pakai media_id
                    price: parseFloat(row.querySelector(".variant-price")?.value || 0),
                    sale: parseFloat(row.querySelector(".variant-sale-price")?.value || 0),
                    sale_start: discountRow?.querySelector(".variant-discount-start")?.value || null,
                    sale_end: discountRow?.querySelector(".variant-discount-end")?.value || null,
                    weight: row.querySelector(".variant-weight")?.value || "",
                    stock: parseInt(row.querySelector(".variant-stock")?.value || 0)
                };
            });

            this.variantsTextarea.value = JSON.stringify(data, null, 2);
        },

        updateVariantsWithImages(attrs, imageMap) {
            if (!attrs.length) {
                this.variantsTableContainer.innerHTML = "";
                this.variantsTextarea.value = "";
                return;
            }

            // Hapus tabel lama sebelum render baru
            this.variantsTableContainer.innerHTML = "";

            this.renderVariantTable(attrs, imageMap);
        },

        // =========================
        // CKEDITOR & WARRANTY
        // =========================
        initEditors() {
            ClassicEditor.create(document.querySelector("#description")).catch(console.error);
            ClassicEditor.create(document.querySelector("#specification")).catch(console.error);
        },

        initWarranty() {
            const sw = document.getElementById("warrantySwitch");
            const inp = document.getElementById("warrantyInput");
            if (!sw || !inp) return;

            inp.disabled = !sw.checked;

            // Event listener untuk perubahan
            sw.addEventListener("change", () => {
                inp.disabled = !sw.checked;
                inp.value = sw.checked ? "Resmi 1 Tahun" : "";
            });
        },

        initUploadTab() {
            // Saat tab "Upload" dibuka, kosongkan preview
            const uploadTab = document.getElementById("upload-tab");
            const uploadPreview = document.getElementById("uploadPreview");
            const uploadInput = document.getElementById("uploadMediaInput");

            uploadTab?.addEventListener("shown.bs.tab", () => {
                uploadInput.value = "";
                uploadPreview.innerHTML = "";
            });
        },

        // =========================
        // FORM SUBMIT
        // =========================
        async submitForm(e) {
            e.preventDefault();
            const form = e.target;
            const res = await fetch(form.action, {
                method: "POST",
                body: new FormData(form)
            });
            console.log(await res.text());
        }
    };

    ProductForm.init();
});