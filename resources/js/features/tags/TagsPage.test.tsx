import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import axios, { AxiosError, type AxiosResponse } from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { TagsPage } from './TagsPage';
import { type TagsPayload } from './types';

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
    };
});

function axiosError(status: number, message?: string) {
    const error = new AxiosError(message ?? 'Request failed');
    error.response = {
        status,
        data: message ? { message } : {},
    } as AxiosResponse;
    return error;
}

const tagsPayload: TagsPayload = {
    status: 'success',
    data: [
        {
            id: 1,
            slug: 'academic',
            name: 'Academic',
            description: 'Academic announcements',
            posts_count: 3,
        },
        { id: 2, slug: 'beasiswa', name: 'Beasiswa', description: null, posts_count: 0 },
    ],
};

function renderPage() {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return render(
        <QueryClientProvider client={queryClient}>
            <TagsPage />
        </QueryClientProvider>
    );
}

describe('TagsPage', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: tagsPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders the heading and a row per tag with description and post count', async () => {
        renderPage();

        expect(await screen.findByRole('heading', { name: 'Daftar Topik' })).toBeInTheDocument();
        expect(
            screen.getByText(
                'Kumpulan topik informasi perkuliahan peserta didik Program Studi Sarjana Informatika Telkom University.'
            )
        ).toBeInTheDocument();
        expect(screen.getByText('Academic (3)')).toBeInTheDocument();
        expect(screen.getByText('Academic announcements')).toBeInTheDocument();
        expect(screen.getByText('Beasiswa (0)')).toBeInTheDocument();
    });

    it('omits the description paragraph for a tag without one', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Daftar Topik' });
        expect(screen.queryByText('Beasiswa announcements')).not.toBeInTheDocument();
    });

    it('links each tag name to its detail page', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Daftar Topik' });

        const academic = screen.getByRole('link', { name: 'Academic (3)' });
        expect(academic).toHaveAttribute('href', '/tags/academic');
        expect(academic).toHaveClass('text-blue-600', 'no-underline');
        expect(screen.getByRole('link', { name: 'Beasiswa (0)' })).toHaveAttribute(
            'href',
            '/tags/beasiswa'
        );
    });

    it('renders a skeleton while loading', async () => {
        let resolveGet: (value: { data: TagsPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        renderPage();

        expect(screen.getByRole('status', { name: /Memuat daftar topik/ })).toBeInTheDocument();

        resolveGet({ data: tagsPayload });

        expect(await screen.findByRole('heading', { name: 'Daftar Topik' })).toBeInTheDocument();
    });

    it('does not nest a div inside a p in the skeleton', async () => {
        let resolveGet: (value: { data: TagsPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        const { container } = renderPage();

        expect(screen.getByRole('status', { name: /Memuat daftar topik/ })).toBeInTheDocument();

        const paragraphs = container.querySelectorAll('p');
        paragraphs.forEach((paragraph) => {
            expect(paragraph.querySelector('div')).toBeNull();
        });

        resolveGet({ data: tagsPayload });
    });

    it('shows an error message when the request fails', async () => {
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

    it('shows an empty state when there are no tags', async () => {
        vi.mocked(axios.get).mockResolvedValue({ data: { status: 'success', data: [] } });

        renderPage();

        expect(await screen.findByText('Belum ada topik.')).toBeInTheDocument();
    });

    it('keeps the constrained article width', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Daftar Topik' });

        const container = screen
            .getByRole('heading', { name: 'Daftar Topik' })
            .closest('.typeset-article');
        expect(container).toHaveClass('max-w-[37em]');
    });
});
