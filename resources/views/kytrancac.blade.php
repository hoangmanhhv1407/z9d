<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CỬU LONG TRANH BÁ - 9DRAGONS</title>
    
    <!-- Favicon cơ bản -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" />
    
    <!-- iOS icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    
    <!-- Android icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}" />
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}" />
    
    <!-- Các kích thước khác -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    
    <!-- Web manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="antialiased bg-gray-100">
    <div id="app" class="flex justify-center items-center min-h-screen">
        <!-- React component sẽ được render ở đây -->
    </div>
    <div id="root"></div>
    <div id="popup-root"></div>
</body>
</html>