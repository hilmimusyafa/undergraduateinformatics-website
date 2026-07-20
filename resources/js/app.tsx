import './bootstrap';
import '../css/app.css';
import React from 'react';
import ReactDOM from 'react-dom/client';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { createRouter, createRootRoute, createRoute, RouterProvider } from '@tanstack/react-router';
import { HelmetProvider } from 'react-helmet-async';
import NProgress from 'nprogress';
import { AppLayout } from './layouts/AppLayout';
import { HomePage } from './pages/HomePage';

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            refetchOnWindowFocus: false,
            retry: 1,
        },
    },
});

const rootRoute = createRootRoute({
    component: AppLayout,
});

const indexRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/',
    component: HomePage,
});

const searchRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/posts/search',
    component: () => null,
});

const linksRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/links',
    component: () => null,
});

const feedbackRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/feedback',
    component: () => null,
});

const adminLoginRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/admin/login',
    component: () => null,
});

const postDetailRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/posts/$id',
    component: () => null,
});

const tagDetailRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/tags/$id',
    component: () => null,
});

const routeTree = rootRoute.addChildren([
    indexRoute,
    searchRoute,
    linksRoute,
    feedbackRoute,
    adminLoginRoute,
    postDetailRoute,
    tagDetailRoute,
]);

const router = createRouter({
    routeTree,
    defaultPreload: 'intent',
});

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
    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <HelmetProvider>
                <QueryClientProvider client={queryClient}>
                    <RouterProvider router={router} />
                </QueryClientProvider>
            </HelmetProvider>
        </React.StrictMode>
    );
}
