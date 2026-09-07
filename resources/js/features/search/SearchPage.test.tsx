import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { SearchPage } from './SearchPage';
import { type PostSearchPayload } from './types';

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

const { navigateMock } = vi.hoisted(() => ({ navigateMock: vi.fn() }));

vi.mock('@tanstack/react-router', async () => {
    const actual =
        await vi.importActual<typeof import('@tanstack/react-router')>('@tanstack/react-router');

    return {
        ...actual,
        createLink: (Comp: any) =>
            function MockedLink({ to, params, ...props }: any) {
                const href =
                    typeof to === 'string' && params
                        ? to.replace(/\$[^/]+/g, (key: string) => params[key.slice(1)] ?? key)
                        : to;

                return <Comp href={href} {...props} />;
            },
        useNavigate: () => navigateMock,
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

function renderPage(q: string, page = 1) {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return render(
        <QueryClientProvider client={queryClient}>
            <SearchPage q={q} page={page} />
        </QueryClientProvider>
    );
}

describe('SearchPage', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: searchPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders a search bar seeded with the query, the result count, and post links', async () => {
        renderPage('beasiswa');

        expect(await screen.findByText('42 hasil')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Cari...')).toHaveValue('beasiswa');
        expect(screen.getByRole('link', { name: 'Pendaftaran Beasiswa 2026' })).toHaveAttribute(
            'href',
            '/posts/pendaftaran-beasiswa-2026'
        );
    });

    it('navigates to the search page with a new query when Enter is pressed in the search bar', async () => {
        const user = userEvent.setup();

        renderPage('beasiswa');

        await screen.findByText('42 hasil');

        const input = screen.getByPlaceholderText('Cari...');
        await user.clear(input);
        await user.type(input, 'kurikulum{enter}');

        expect(navigateMock).toHaveBeenCalledWith({
            to: '/posts/search',
            search: { q: 'kurikulum' },
        });
    });

    it('navigates to the next page when Lanjut is clicked', async () => {
        renderPage('beasiswa');

        await screen.findByText('42 hasil');

        screen.getByRole('button', { name: 'Lanjut' }).click();

        expect(navigateMock).toHaveBeenCalledWith({
            to: '/posts/search',
            search: { q: 'beasiswa', page: 2 },
        });
    });

    it('renders a skeleton while loading', async () => {
        let resolveGet: (value: { data: PostSearchPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        renderPage('beasiswa');

        expect(screen.getByRole('status', { name: /Memuat hasil pencarian/ })).toBeInTheDocument();

        resolveGet({ data: searchPayload });

        expect(await screen.findByText('42 hasil')).toBeInTheDocument();
    });

    it('shows an error message when the request fails', async () => {
        vi.mocked(axios.get).mockRejectedValue(new Error('Request failed'));

        renderPage('beasiswa');

        expect(
            await screen.findByText(
                'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.',
                {},
                { timeout: 3000 }
            )
        ).toBeInTheDocument();
    });
});
