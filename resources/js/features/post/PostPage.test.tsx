import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { axiosError } from '@/test/mocks';

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
            <PostPage slug="pendaftaran-beasiswa-2026" />
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

    it('renders a skeleton while loading', async () => {
        let resolveGet: (value: { data: PostPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        renderPage();

        expect(screen.getByRole('status', { name: 'Memuat detail informasi' })).toBeInTheDocument();

        resolveGet({ data: detailPayload });

        expect(
            await screen.findByRole('heading', { name: 'Pendaftaran Beasiswa 2026' })
        ).toBeInTheDocument();
    });

    it('shows the post not-found state for a 404 response', async () => {
        vi.mocked(axios.get).mockRejectedValue(axiosError(404));

        renderPage();

        expect(
            await screen.findByRole(
                'heading',
                { name: 'Informasi tidak ditemukan' },
                { timeout: 3000 }
            )
        ).toBeInTheDocument();
    });

    it('shows the generic error for other failures', async () => {
        vi.mocked(axios.get).mockRejectedValue(axiosError(500));

        renderPage();

        expect(
            await screen.findByText(
                'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.',
                {},
                { timeout: 3000 }
            )
        ).toBeInTheDocument();
    });
});
