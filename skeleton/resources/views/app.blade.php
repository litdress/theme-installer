<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @yield('meta')
    
    {{-- Styles --}}
    <x-styles/>
    <link rel="stylesheet" href="{{asset('css/app.css')}}?v={{filemtime('css/app.css')}}">

</head>
<body>

    <div class="container">
        @include('partials.header.header')

        <main>
            @yield('content')
        </main>
    </div>
    

    @include('partials.footer.footer')

    {{-- Scripts --}}
    <script src="{{asset('js/app.js')}}?v={{filemtime('js/app.js')}}"></script>
    <x-scripts/>

</body>
</html>