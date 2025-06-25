import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        screens: {
            sm: "640px",
            md: "768px",
            lg: "1025px", // Змінено з 1024px на 1025px
            xl: "1280px",
        },
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('flowbite/plugin')({
            // Налаштування для низького пріоритету
            charts: true,
            forms: true,
            tooltips: true,
            popover: true,
            modal: true,
            dropdown: true,
            navbar: true,
            carousel: true,
            accordion: true,
            tabs: true,
            rating: true,
            timeline: true,
            progress: true
        })
    ],
    // Додаємо важливість для власних стилів
    important: false,
};
