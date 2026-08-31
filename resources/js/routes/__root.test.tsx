import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { RouterProvider, createRouter } from '@tanstack/react-router';

import { render, waitFor } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { routeTree } from '../routeTree.gen';

vi.mock('axios');

describe('RootLayout', () => {
    let queryClient: QueryClient;

    beforeEach(() => {
        queryClient = new QueryClient({
            defaultOptions: {
                queries: {
                    retry: false,
                },
            },
        });
        (window as any).__INITIAL_DATA__ = {
            title: 'Beranda - Portal Informasi',
            description: 'Deskripsi Portal',
            tags: [],
            posts: [],
            sections: [],
        };
    });

    it('renders outlet wrapped in max-w-7xl mx-auto container', async () => {
        const router = createRouter({
            routeTree,
        });

        const { container } = render(
            React.createElement(
                QueryClientProvider,
                { client: queryClient },
                React.createElement(RouterProvider, { router })
            )
        );

        await waitFor(() => {
            const wrapper = container.querySelector('.max-w-7xl');
            expect(wrapper).not.toBeNull();
            expect(wrapper).toHaveClass('mx-auto', 'w-full', 'max-w-7xl');
        });
    });
});
