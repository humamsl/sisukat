import './bootstrap';
import Alpine from 'alpinejs';

window.setSisukatTheme = function (theme) {
    localStorage.setItem('sisukat-theme', theme);
    const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark', isDark);
};

window.Alpine = Alpine;
Alpine.start();
