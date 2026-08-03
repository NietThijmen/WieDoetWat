import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import inertia from '@inertiajs/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Noto Serif', {
                    weights: [100, 200, 300, 400, 500, 600, 700, 800, 900],
                    styles: ['normal', 'italic'],
                }),
                bunny('Nunito', {
                    weights: [200, 300, 400, 500, 600, 700, 800, 900, 1000],
                    styles: ['normal', 'italic'],
                }),
            ],
        }),
        tailwindcss(),
        svelte(),
        inertia(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
