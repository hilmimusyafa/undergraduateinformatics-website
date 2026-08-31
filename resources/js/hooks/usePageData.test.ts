import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { renderHook, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { usePageData } from './usePageData';

vi.mock('axios');

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
        expect(axios.get).toHaveBeenCalledWith('/api/home');
    });
});
