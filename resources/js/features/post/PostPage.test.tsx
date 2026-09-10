import { Suspense } from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { PostPage } from './PostPage';
import { type PostPayload } from './types';

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

vi.mock('@tanstack/react-router', async () => {
    const { routerModuleMock } = await import('@/test/mocks');

    return routerModuleMock();
});

const detailPayload: PostPayload = {
    status: 'success',
    data: {
        id: 7,
        slug: 'pendaftaran-beasiswa-2026',
        title: 'Pendaftaran Beasiswa 2026',
        subtitle: 'Periode baru dibuka',
        body: '<p>Informasi pendaftaran beasiswa 2026.</p>',
        image: 'images/placeholder.png',
        created_at: '2026-09-01T12:00:00.000Z',
        updated_at: '2026-09-05T12:00:00.000Z',
        tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
    },
};

function renderPage() {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return render(
        <QueryClientProvider client={queryClient}>
            <Suspense fallback={null}>
                <PostPage slug="pendaftaran-beasiswa-2026" />
            </Suspense>
        </QueryClientProvider>
    );
}

describe('PostPage', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: detailPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders the post article on success', async () => {
        renderPage();

        expect(
            await screen.findByRole('heading', { name: 'Pendaftaran Beasiswa 2026' })
        ).toBeInTheDocument();
        expect(screen.getByText('Diperbarui 5 Sep 2026')).toBeInTheDocument();
    });
});
