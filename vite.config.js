import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { TanStackRouterVite } from '@tanstack/router-plugin/vite';

export default defineConfig({
    plugins: [
        TanStackRouterVite({
            routesDirectory: './resources/js/routes',
            generatedRouteTree: './resources/js/routeTree.gen.ts',
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@/': '/resources/js/',
        },
    },
    test: {
        environment: 'jsdom',
        setupFiles: './resources/js/test/setup.ts',
        globals: true,
    },
});
