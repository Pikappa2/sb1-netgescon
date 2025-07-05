import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class', // Abilita dark mode con classe
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colori personalizzabili per la GUI
                'custom-bg': 'var(--custom-bg)',
                'custom-text': 'var(--custom-text)',
                'custom-accent': 'var(--custom-accent)',
                'sidebar-bg': 'var(--sidebar-bg)',
                'sidebar-text': 'var(--sidebar-text)',
                'sidebar-accent': 'var(--sidebar-accent)',
            }
        },
    },
    plugins: [forms],
};
