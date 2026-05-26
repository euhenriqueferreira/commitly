<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.imports.head')
    </head>
    <body class="font-inter w-screen h-dvh">

        <div x-data="scrollNav()" class="relative h-screen w-full max-w-md mx-auto overflow-hidden">
            <main x-ref="scrollArea" x-on:scroll="onScroll" class="w-full h-full px-4 pt-22 pb-20 bg-background-primary overflow-y-auto overscroll-contain scrollbar-hide">
                <div class="w-full flex flex-col gap-3">
                    {{ $slot }}
                </div>
            </main>

            @unless(isset($hideBottomNav))
                <nav x-bind:class="bottomHidden ? 'translate-y-20 opacity-0 pointer-events-none' : 'translate-y-0 opacity-100'" class="absolute bottom-3 left-3 right-3 z-20 bg-background-secondary border border-primary rounded-xl transition">
                    <livewire:structure.bottom-navigation-bar />
                </nav>
            @endunless
        </div>

        @livewireScripts
    </body>
</html>
