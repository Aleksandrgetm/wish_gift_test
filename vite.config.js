import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Cormorant Garamond', {
                    weights: [400, 500],
                    styles: ['normal', 'italic'],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                }),
                bunny('Manrope', {
                    weights: [400, 500, 600],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                }),
            ],
        }),
        tailwindcss(),
        vue(),
        vuetify({ autoImport: true }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
