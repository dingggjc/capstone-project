import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        "node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx,vue}",
        "node_modules/flowbite/**/*.{js,jsx,ts,tsx}",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            maxHeight: {
                70: "17.5rem", // Custom max-height value for 70
                80: "20rem", // Custom max-height value for 80
                100: "25rem", // Custom max-height value for 100
                120: "30rem", // Custom max-height value for 120
                130: "32.5rem", // Custom max-height value for 130
                135: "33.75rem", // Custom max-height value for 135
                140: "35rem", // Custom max-height value for 140
                150: "37.5rem", // Custom max-height value for 150
            },
            colors: {
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                    950: "#172554",
                },
            },
            fontFamily: {
                sans: [
                    "Inter",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "system-ui",
                    "Segoe UI",
                    "Roboto",
                    "Helvetica Neue",
                    "Arial",
                    "Noto Sans",
                    "sans-serif",
                    "Apple Color Emoji",
                    "Segoe UI Emoji",
                    "Segoe UI Symbol",
                    "Noto Color Emoji",
                ],
            },
        },
    },
    plugins: [
        forms,
        require("flowbite/plugin")({
            datatables: true,
        }),
        require("flowbite/plugin"),
    ],
};
