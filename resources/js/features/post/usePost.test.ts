import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { renderHook, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { type PostPayload } from './types';
import { usePost } from './usePost';

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

const detailPayload: PostPayload = {
    status: 'success',
    data: {
        id: 7,
        slug: 'pendaftaran-beasiswa-2026',
        title: 'Pendaftaran Beasiswa 2026',
        subtitle: 'Periode baru dibuka',
        body: '<p>Informasi pendaftaran beasiswa 2026.</p>',
        image: null,
        created_at: '2026-09-01T12:00:00.000Z',
        updated_at: '2026-09-05T12:00:00.000Z',
        tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
    },
};

function renderUsePost(slug: string) {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return renderHook(() => usePost(slug), {
        wrapper: ({ children }) =>
            React.createElement(QueryClientProvider, { client: queryClient }, children),
    });
}

describe('usePost', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: detailPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('fetches the post detail for the given slug', async () => {
        renderUsePost('pendaftaran-beasiswa-2026');

        await waitFor(() => {
            expect(axios.get).toHaveBeenCalledWith('/api/posts/pendaftaran-beasiswa-2026');
        });
    });

    it('selects the data field of the response', async () => {
        const { result } = renderUsePost('pendaftaran-beasiswa-2026');

        await waitFor(() => {
            expect(result.current.data).toEqual(detailPayload.data);
        });
    });
});
