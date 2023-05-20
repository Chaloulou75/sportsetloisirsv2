const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                darkblue: "#151f32",
                midnight: "#121b4f",
                gold: "#ffd700",
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("prettier-plugin-tailwindcss"),
    ],
};
