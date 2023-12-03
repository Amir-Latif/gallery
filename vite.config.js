import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/styles/global.css",
                "resources/scripts/global.js",
                "resources/styles/filepond.css",
                "resources/scripts/filepond.js",
                "resources/scripts/filepond.min.js",
                "resources/scripts/filepond-jquery.js",
                "resources/styles/filepond-plugin-image-preview.css",
                "resources/scripts/filepond-plugin-image-preview.js",
                "resources/scripts/jquery.js",
            ],
            refresh: true,
        }),
    ],
});
