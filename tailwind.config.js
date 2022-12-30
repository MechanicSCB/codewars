const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ui: {
                    // body: 'rgb(var(--color-ui-bg) / <alpha-value>)',
                    // section: 'rgb(var(--color-ui-section) / <alpha-value>)',
                    // accent: 'rgb(var(--color-ui-accent) / <alpha-value>)',
                    body: 'var(--color-ui-bg)',
                    section: 'var(--color-ui-section)',
                    accent: 'var(--color-ui-accent)',
                    text: 'var(--color-ui-text)',
                    link: {
                        text: 'var(--color-ui-link-text)',
                        hover: 'var(--color-ui-link-text-hover)',
                        active: 'var(--color-ui-link-text-active)',
                        disabled: 'var(--color-ui-link-text-disabled)',
                        alt: {
                            text: 'var(--color-ui-alt-link-text)',
                            hover: 'var(--color-ui-alt-link-text-hover)',
                            active: 'var(--color-ui-alt-link-text-active)',
                        },

                    },
                },
                gray: {
                    '50': '#fafafa',
                    '100': '#f4f4f5',
                    '200': '#e4e4e7',
                    '300': '#d4d4d8',
                    '400': '#a1a1aa',
                    '500': '#71717a',
                    '600': '#52525b',
                    '700': '#3f3f46',
                    '800': '#27272a',
                    '900': '#18181b',
                },

            },
            fontSize: {
                'xxs': '.6rem',
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
