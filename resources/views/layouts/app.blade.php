<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.imports.head')
    </head>
    <body class="font-inter w-screen h-dvh">

        <main class="w-full h-full px-4 pt-11 pb-20 bg-background-primary overflow-y-auto">
            <div class="w-full flex flex-col gap-3">
                {{ $slot }}
            </div>
        </main>

        @livewireScripts
    </body>
</html>
