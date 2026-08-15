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
                bunny('Playfair Display', {
                    weights: [400, 500, 600],
                    styles: ['normal', 'italic'],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                }),
                bunny('Source Sans Pro', {
                    weights: [400, 600],
                    styles: ['normal'],
                    subsets: ['latin', 'latin-ext', 'cyrillic', 'cyrillic-ext'],
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
