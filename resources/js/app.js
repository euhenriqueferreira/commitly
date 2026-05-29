// import Alpine from 'alpinejs'

import { scrollNav } from './scroll-nav'

Alpine.data('scrollNav', scrollNav)

function applyTheme() {
    const theme = localStorage.getItem('theme');

    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

applyTheme();

document.addEventListener('livewire:navigated', applyTheme);
