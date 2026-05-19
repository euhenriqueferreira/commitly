<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.imports.head')
    </head>
    <body class="font-inter w-screen h-screen">

        <main class="w-full h-full px-6 py-12 bg-background-primary">
            <div class="w-full h-full flex flex-col gap-8 items-center justify-center">
                {{ $slot }}
            </div>
        </main>

        @livewireScripts
    </body>
</html>
