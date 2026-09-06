import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import axios, { AxiosError, type AxiosResponse } from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { TagDetailPage } from './TagDetailPage';
import { type TagWithPostsPayload } from './types';

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

const detailPayload: TagWithPostsPayload = {
    status: 'success',
    data: {
        id: 1,
        slug: 'beasiswa',
        name: 'Beasiswa',
        description: 'Informasi beasiswa dalam dan luar negeri',
        posts: [
            {
                id: 8,
                slug: 'beasiswa-luar-negeri',
                title: 'Beasiswa Luar Negeri',
                subtitle: 'Daftar sekarang',
                updated_at: '2026-09-01T12:00:00.000Z',
                tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
            },
            {
                id: 7,
                slug: 'pendaftaran-beasiswa-2026',
                title: 'Pendaftaran Beasiswa 2026',
                subtitle: 'Periode baru dibuka',
                updated_at: '2026-04-05T12:00:00.000Z',
                tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
            },
            {
                id: 3,
                slug: 'beasiswa-2024',
                title: 'Beasiswa 2024',
                subtitle: 'Periode lama',
                updated_at: '2024-01-10T12:00:00.000Z',
                tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
            },
        ],
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
            <TagDetailPage slug="beasiswa" />
        </QueryClientProvider>
    );
}

describe('TagDetailPage', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: detailPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders the tag name, description, and a month heading per post group', async () => {
        renderPage();

        expect(await screen.findByRole('heading', { name: 'Beasiswa' })).toBeInTheDocument();
        expect(screen.getByText('Informasi beasiswa dalam dan luar negeri')).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'September 2026' })).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'April 2026' })).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Januari 2024' })).toBeInTheDocument();
    });

    it('groups posts under their month heading and sorts months descending', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });

        const headings = screen.getAllByRole('heading').map((heading) => heading.textContent);
        const monthIndexes = [
            headings.indexOf('September 2026'),
            headings.indexOf('April 2026'),
            headings.indexOf('Januari 2024'),
        ];
        expect(monthIndexes).toEqual([...monthIndexes].sort((a, b) => a - b));

        const septemberHeading = screen.getByRole('heading', { name: 'September 2026' });
        expect(septemberHeading.closest('section')?.textContent).toContain('Beasiswa Luar Negeri');
        expect(
            screen.getByRole('heading', { name: 'September 2026' }).closest('section')?.textContent
        ).not.toContain('Pendaftaran Beasiswa 2026');
    });

    it('links each post title to its detail page', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });

        expect(screen.getByRole('link', { name: 'Pendaftaran Beasiswa 2026' })).toHaveAttribute(
            'href',
            '/posts/pendaftaran-beasiswa-2026'
        );
        expect(screen.getByRole('link', { name: 'Beasiswa Luar Negeri' })).toHaveAttribute(
            'href',
            '/posts/beasiswa-luar-negeri'
        );
    });

    it('omits the description paragraph when absent', async () => {
        vi.mocked(axios.get).mockResolvedValue({
            data: { status: 'success', data: { ...detailPayload.data, description: null } },
        });

        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });
        expect(
            screen.queryByText('Informasi beasiswa dalam dan luar negeri')
        ).not.toBeInTheDocument();
    });

    it('renders a skeleton while loading', async () => {
        let resolveGet: (value: { data: TagWithPostsPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        renderPage();

        expect(screen.getByRole('status', { name: /Memuat detail topik/ })).toBeInTheDocument();

        resolveGet({ data: detailPayload });

        expect(await screen.findByRole('heading', { name: 'Beasiswa' })).toBeInTheDocument();
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

    it('shows an empty state with the intro and a message when the tag has no posts', async () => {
        vi.mocked(axios.get).mockResolvedValue({
            data: { status: 'success', data: { ...detailPayload.data, posts: [] } },
        });

        renderPage();

        expect(
            await screen.findByRole('heading', { name: 'Beasiswa' }, { timeout: 3000 })
        ).toBeInTheDocument();
        expect(screen.getByText('Belum ada postingan untuk topik ini.')).toBeInTheDocument();
    });

    it('renders a Daftar Isi navigation in the mobile flow and the desktop sidebar', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });

        const navigations = screen.getAllByRole('navigation', { name: 'Daftar Isi' });
        expect(navigations).toHaveLength(2);
        expect(navigations.filter((navigation) => navigation.closest('aside'))).toHaveLength(1);
        expect(navigations.filter((navigation) => navigation.closest('.lg\\:hidden'))).toHaveLength(
            1
        );
        expect(screen.getAllByRole('button', { name: 'September 2026' })).toHaveLength(2);
        expect(screen.getAllByRole('button', { name: 'April 2026' })).toHaveLength(2);
        expect(screen.getAllByRole('button', { name: 'Januari 2024' })).toHaveLength(2);
    });

    it('gives each month heading an id and scroll margin for anchor navigation', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });

        const septemberHeading = screen.getByRole('heading', { name: 'September 2026' });
        expect(septemberHeading).toHaveAttribute('id', 'tag-section-2026-09');
        expect(septemberHeading).toHaveClass('scroll-mt-28', 'md:scroll-mt-27');
        expect(screen.getByRole('heading', { name: 'April 2026' })).toHaveAttribute(
            'id',
            'tag-section-2026-04'
        );
    });

    it('scrolls to the month section when a table of contents item is clicked', async () => {
        const scrollIntoView = vi.mocked(Element.prototype.scrollIntoView);
        scrollIntoView.mockClear();

        renderPage();

        await screen.findByRole('heading', { name: 'Beasiswa' });

        screen.getAllByRole('button', { name: 'September 2026' })[0].click();

        expect(scrollIntoView).toHaveBeenCalledTimes(1);
        expect(scrollIntoView).toHaveBeenCalledWith({ behavior: 'smooth', block: 'start' });
        expect(scrollIntoView.mock.instances[0]).toBe(
            document.getElementById('tag-section-2026-09')
        );
    });
});
