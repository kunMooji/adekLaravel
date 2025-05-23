<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @stack('styles') 
</head>
<body>
    <main>
        @yield('content') 
    </main>

    <footer>
        @include('footer') 
    </footer>

    @stack('scripts') 
</body>
</html>
