<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ $title ?? config('app.name') }}</title>

<script>
    (() => {
        const theme = localStorage.getItem('theme');

        if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@livewireStyles