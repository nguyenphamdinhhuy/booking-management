<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
</head>

<body>
@include('admin/layouts/header')
@include('admin/layouts/sidebar')

<main class="main-content">
    @yield('content')
</main>

<script src="{{ asset('admin/js/script.js') }}"></script>
</body>

</html>