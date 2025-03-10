<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>9DRAGONS - CỬU LONG TRANH BÁ</title>
    <meta name="description" content="9D , 9DRAGONS - CỬU LONG TRANH BÁ" />
    <meta name="keywords" content="9D , 9DRAGONS , cửu long , cửu long tranh bá" />
    <meta property="og:description" content="9D , 9DRAGONS- CỬU LONG TRANH BÁ" />

    
    @yield('css')
</head>

<body>



    <div class="wrap-bg">
        @include('frontend.layout.head')
        <div class="wrapper">
            @yield('content')
        </div>
        @include('frontend.layout.footer')
    </div> 
    
    @yield('script')
    @stack('scripts')
    @include('frontend.message')
</body>

</html>
