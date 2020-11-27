const { theme: defaultTheme, variants } = require('tailwindcss/defaultConfig');

module.exports = {
    darkMode: 'class',
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        display: [...variants.display, 'group-hover'],
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        textColor: [...variants.textColor, 'group-hover'],
    },

    plugins: [require('@tailwindcss/ui'), require('@tailwindcss/forms')],
};
