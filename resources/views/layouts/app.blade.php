<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.imports.head')
    </head>
    <body class="font-inter">

        {{ $slot }}

        @livewireScripts
    </body>
</html>
