import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { renderHook, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { usePageData } from './usePageData';

vi.mock('axios', async () => {
    const actual = await vi.importActual<typeof import('axios')>('axios');

    return {
        ...actual,
        default: {
            ...actual.default,
            get: vi.fn(),
        },
    };
});

describe('usePageData', () => {
    let queryClient: QueryClient;

    beforeEach(() => {
        queryClient = new QueryClient({
            defaultOptions: {
                queries: {
                    retry: false,
                },
            },
        });
        vi.clearAllMocks();
        delete (window as any).__INITIAL_DATA__;
    });

    const createWrapper =
        () =>
        ({ children }: { children: React.ReactNode }) =>
            React.createElement(QueryClientProvider, { client: queryClient }, children);

    it('hydrates from window.__INITIAL_DATA__ without calling axios and clears initial data', async () => {
        const initialPayload = {
            tags: [{ id: 1, name: 'AI', description: 'Artificial Intelligence' }],
            posts: [],
            sections: [],
        };
        (window as any).__INITIAL_DATA__ = initialPayload;

        const { result } = renderHook(() => usePageData('/api/home'), {
            wrapper: createWrapper(),
        });

        await waitFor(() => {
            expect(result.current.data).toEqual(initialPayload);
        });
        expect(axios.get).not.toHaveBeenCalled();
        expect((window as any).__INITIAL_DATA__).toBeNull();
    });

    it('fetches data using axios when window.__INITIAL_DATA__ is null', async () => {
        const fetchedPayload = {
            tags: [{ id: 2, name: 'Cybersecurity', description: 'Security labs' }],
            posts: [],
            sections: [],
        };
        (axios.get as any).mockResolvedValueOnce({ data: fetchedPayload });

        const { result } = renderHook(() => usePageData('/api/home'), {
            wrapper: createWrapper(),
        });

        await waitFor(() => {
            expect(result.current.data).toEqual(fetchedPayload);
        });
        expect(axios.get).toHaveBeenCalledWith('/api/home', { params: undefined });
    });

    it('passes query params to axios and includes them in the cache key', async () => {
        const fetchedPayload = {
            status: 'success',
            data: [],
            meta: { current_page: 1, per_page: 10, total: 0, last_page: 1 },
        };
        (axios.get as any).mockResolvedValueOnce({ data: fetchedPayload });

        const { result } = renderHook(
            () => usePageData('/api/posts/search', {}, { q: 'beasiswa', page: 2 }),
            { wrapper: createWrapper() }
        );

        await waitFor(() => {
            expect(result.current.data).toEqual(fetchedPayload);
        });
        expect(axios.get).toHaveBeenCalledWith('/api/posts/search', {
            params: { q: 'beasiswa', page: 2 },
        });
    });

    it('does not reuse initial data when the query params differ', async () => {
        (window as any).__INITIAL_DATA__ = {
            status: 'success',
            data: [{ id: 1 }],
            meta: {},
        };
        const fetchedPayload = { status: 'success', data: [{ id: 2 }], meta: {} };
        (axios.get as any).mockResolvedValueOnce({ data: fetchedPayload });

        const { result, rerender } = renderHook(
            ({ params }) => usePageData('/api/posts/search', {}, params),
            {
                initialProps: { params: { q: 'beasiswa', page: 1 } },
                wrapper: createWrapper(),
            }
        );

        await waitFor(() => {
            expect(result.current.data).toEqual({ status: 'success', data: [{ id: 1 }], meta: {} });
        });
        expect(axios.get).not.toHaveBeenCalled();

        rerender({ params: { q: 'lama', page: 1 } });

        await waitFor(() => {
            expect(result.current.data).toEqual(fetchedPayload);
        });
        expect(axios.get).toHaveBeenCalled();
    });

    it('reports a 404 error without calling axios when the server sends the not-found marker', async () => {
        (window as any).__INITIAL_DATA__ = { notFound: true };

        const { result } = renderHook(() => usePageData('/api/home'), {
            wrapper: createWrapper(),
        });

        await waitFor(() => {
            expect(result.current.isError).toBe(true);
        });
        expect(axios.get).not.toHaveBeenCalled();
        expect((result.current.error as any).response?.status).toBe(404);
        expect((window as any).__INITIAL_DATA__).toBeNull();
    });
});
