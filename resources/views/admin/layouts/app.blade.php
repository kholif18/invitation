<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Metronic CSS -->
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    @stack('styles')
</head>
<body>
    <!-- Sidebar / Header bisa di sini -->

    <div class="container">
        @yield('content')
    </div>

    <!-- Metronic JS -->
    <script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>
    @stack('scripts')
</body>
</html>
