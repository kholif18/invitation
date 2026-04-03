@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Settings</li>
@endsection

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-6">

        {{-- ================= GENERAL ================= --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Website</h3>
                </div>
                <div class="card-body row">

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Nama Website</label>
                        <input type="text" class="form-control" name="site_name" value="{{ old('site_name', setting('site_name')) }}">
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Email Admin</label>
                        <input type="email" class="form-control" name="admin_email" value="{{ old('admin_email', setting('admin_email')) }}">
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">WhatsApp</label>
                        <input type="text" class="form-control" name="admin_whatsapp" value="{{ old('admin_whatsapp', setting('admin_whatsapp')) }}">
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Base URL Undangan</label>
                        <input type="text" class="form-control" name="base_url" value="{{ old('base_url', setting('base_url')) }}">
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Logo</label>

                        <div class="mb-2">
                            <img id="logoPreview"
                                src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('admin/assets/media/logos/logo.svg') }}"
                                height="60">
                        </div>

                        <input type="file" class="form-control" name="site_logo" onchange="previewImage(event, 'logoPreview')">
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Favicon</label>

                        <div class="mb-2">
                            <img id="faviconPreview"
                                src="{{ setting('favicon') ? asset('storage/' . setting('favicon')) : asset('admin/assets/media/logos/favicon.svg') }}"
                                height="40">
                        </div>

                        <input type="file" class="form-control" name="favicon" onchange="previewImage(event, 'faviconPreview')">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= SYSTEM ================= --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System</h3>
                </div>  
                <div class="card-body row">

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Maintenance Mode</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="maintenance_mode" value="1" {{ old('maintenance_mode', setting('maintenance_mode')) ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="fw-bold mb-2">Limit Karakter Ucapan</label>
                        <input type="number" class="form-control" name="wish_limit" value="{{ old('wish_limit', setting('wish_limit', 200)) }}" placeholder="200">
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="d-flex justify-content-end mt-6">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Pengaturan
        </button>
    </div>

</form>
@endsection
<script>
    function previewImage(event, targetId) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById(targetId).src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

