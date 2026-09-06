import React from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { renderHook, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { type TagWithPostsPayload } from './types';
import { useTagDetail } from './useTagDetail';

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

const detailPayload: TagWithPostsPayload = {
    status: 'success',
    data: {
        id: 1,
        slug: 'beasiswa',
        name: 'Beasiswa',
        description: 'Info beasiswa',
        posts: [
            {
                id: 7,
                slug: 'pendaftaran-beasiswa-2026',
                title: 'Pendaftaran Beasiswa 2026',
                subtitle: 'Periode baru dibuka',
                updated_at: '2026-09-05T12:00:00.000Z',
                tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
            },
        ],
    },
};

function renderUseTagDetail(slug: string) {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return renderHook(() => useTagDetail(slug), {
        wrapper: ({ children }) =>
            React.createElement(QueryClientProvider, { client: queryClient }, children),
    });
}

describe('useTagDetail', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: detailPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('fetches the tag detail for the given slug', async () => {
        renderUseTagDetail('beasiswa');

        await waitFor(() => {
            expect(axios.get).toHaveBeenCalledWith('/api/tags/beasiswa');
        });
    });

    it('selects the data field of the response', async () => {
        const { result } = renderUseTagDetail('beasiswa');

        await waitFor(() => {
            expect(result.current.data).toEqual(detailPayload.data);
        });
    });
});
