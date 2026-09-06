import path from 'path';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    cacheDir: 'node_modules/.vite-ladle',
    plugins: [tailwindcss()],
    resolve: {
        alias: {
            '@': path.resolve(process.cwd(), 'resources/js'),
        },
    },
});