<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Client - @yield('title')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Kita buat container full screen biar rapi --}}
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        @yield('content')
    </div>

</body>
</html>