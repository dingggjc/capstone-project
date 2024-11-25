import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            "@Pages": "/resources/js/Pages",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    optimizeDeps: {
        include: [
            "sweetalert2",
            "@inertiajs/inertia",
            "@inertiajs/inertia-vue3",
            "axios",
            "pdfmake/build/pdfmake",
            "pdfmake/build/vfs_fonts",
        ],
    },
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
