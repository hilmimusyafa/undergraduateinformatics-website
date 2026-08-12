import React from 'react';
import ReactDOM from 'react-dom/client';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { RouterProvider, createRouter } from '@tanstack/react-router';

import NProgress from 'nprogress';

import '../css/app.css';
import './bootstrap';
import { routeTree } from './routeTree.gen';

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            refetchOnWindowFocus: false,
            retry: 1,
        },
    },
});

const router = createRouter({
    routeTree,
    defaultPreload: 'intent',
});

NProgress.configure({ showSpinner: false });

router.subscribe('onBeforeLoad', () => {
    NProgress.start();
});

router.subscribe('onLoad', () => {
    NProgress.done();
});

declare module '@tanstack/react-router' {
    interface Register {
        router: typeof router;
    }
}

const rootElement = document.getElementById('root');

if (rootElement) {
    // Remove server-rendered meta tags to let React 19 manage them without duplication
    document.head.querySelectorAll('[data-ssr="true"]').forEach((el) => el.remove());

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <QueryClientProvider client={queryClient}>
                <RouterProvider router={router} />
            </QueryClientProvider>
        </React.StrictMode>
    );
}
