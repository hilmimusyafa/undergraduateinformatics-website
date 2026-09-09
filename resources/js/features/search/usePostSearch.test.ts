import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { renderHook, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { type PostSearchPayload } from './types';
import { usePostSearch } from './usePostSearch';

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

const searchPayload: PostSearchPayload = {
    status: 'success',
    data: [
        {
            id: 7,
            slug: 'pendaftaran-beasiswa-2026',
            title: 'Pendaftaran Beasiswa 2026',
            subtitle: 'Periode baru dibuka',
            updated_at: '2026-09-05T12:00:00.000Z',
            tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
        },
    ],
    meta: { current_page: 1, per_page: 10, total: 42, last_page: 5 },
};

function renderUsePostSearch(q: string, page: number) {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return renderHook(() => usePostSearch(q, page), {
        wrapper: ({ children }) =>
            React.createElement(QueryClientProvider, { client: queryClient }, children),
    });
}

describe('usePostSearch', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: searchPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('fetches the search endpoint with the query params', async () => {
        renderUsePostSearch('beasiswa', 2);

        await waitFor(() => {
            expect(axios.get).toHaveBeenCalledWith('/api/posts/search', {
                params: { q: 'beasiswa', page: 2 },
            });
        });
    });

    it('selects posts and meta from the response', async () => {
        const { result } = renderUsePostSearch('beasiswa', 1);

        await waitFor(() => {
            expect(result.current.data).toEqual({
                posts: searchPayload.data,
                meta: searchPayload.meta,
            });
        });
    });
});
