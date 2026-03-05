import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            keyframes: {
                slideUpFade: {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                }
            },
            animation: {
                slideUpFade: 'slideUpFade 0.6s ease-out forwards',
            },
            colors: {
                primary: {
                    DEFAULT: '#1183d4',
                    light: '#e0f2fe',
                    dark: '#0c6ab0',
                    content: '#ffffff',
                },
                'background-light': '#f6f7f8',
                'background-dark': '#101a22',
                sand: {
                    DEFAULT: '#e8e1d9',
                    dark: '#d1c7bc',
                },
                charcoal: '#1c2630',
                surface: {
                    light: '#ffffff',
                    dark: '#1a2630',
                },
                border: {
                    light: '#e7eef3',
                    dark: '#2d3b45',
                },
                'text-main': '#0d161b',
                'text-secondary': '#4c799a',
                status: {
                    approved: '#078838',
                    pending: '#d97706',
                    rejected: '#374151',
                },
                sage: {
                    DEFAULT: '#8da399',
                    dark: '#738a80',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', 'serif'],
            },
            borderRadius: {
                DEFAULT: '0.25rem',
                lg: '0.5rem',
                xl: '0.75rem',
                '2xl': '1rem',
                full: '9999px',
            },
        },
    },

    plugins: [forms, containerQueries],
};
