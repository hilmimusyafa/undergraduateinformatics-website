import { TanStackRouterVite } from '@tanstack/router-plugin/vite';

import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: { host: '127.0.0.1', port: 5173, strictPort: true },
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
