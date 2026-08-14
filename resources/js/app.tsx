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
    document.head
        .querySelectorAll('[data-ssr="true"]:not([property^="og:"])')
        .forEach((el) => el.remove());

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <QueryClientProvider client={queryClient}>
                <RouterProvider router={router} />
            </QueryClientProvider>
        </React.StrictMode>
    );
}
