import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import axios, { AxiosError, type AxiosResponse } from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { LinksPage } from './LinksPage';
import { type LinksPayload } from './types';

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
            function MockedLink({ to, ...props }: any) {
                return <Comp href={to} {...props} />;
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

const linksPayload: LinksPayload = {
    status: 'success',
    data: [
        {
            id: 1,
            name: 'Kumpulan Link MBKM',
            order_number: 1,
            links: [
                {
                    id: 3,
                    name: 'Angkatan 2020',
                    link: 'http://bit.ly/MBKM2020',
                    updated_at: '2026-09-03T10:00:00.000000Z',
                },
                {
                    id: 4,
                    name: 'Panduan Pendaftaran',
                    link: 'https://example.com/daftar',
                    updated_at: '2026-09-04T10:00:00.000000Z',
                },
            ],
        },
        {
            id: 2,
            name: 'Akademik',
            order_number: 2,
            links: [],
        },
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
            <LinksPage />
        </QueryClientProvider>
    );
}

describe('LinksPage', () => {
    beforeEach(() => {
        vi.mocked(axios.get).mockResolvedValue({ data: linksPayload });
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders the heading, description, a section heading per section, and its links', async () => {
        renderPage();

        expect(
            await screen.findByRole('heading', { name: 'Tautan Penting' }, { timeout: 3000 })
        ).toBeInTheDocument();
        expect(
            screen.getByText(
                'Jelajahi tautan penting pendukung perkuliahan peserta didik Program Studi Sarjana Informatika Telkom University.'
            )
        ).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Kumpulan Link MBKM' })).toBeInTheDocument();
        expect(screen.getByText('Angkatan 2020')).toBeInTheDocument();
        expect(screen.getByText('Panduan Pendaftaran')).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Akademik' })).toBeInTheDocument();
    });

    it('renders each link as an anchor with its href and opens in a new tab', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        const link = screen.getByRole('link', { name: 'Angkatan 2020' });
        expect(link).toHaveAttribute('href', 'http://bit.ly/MBKM2020');
        expect(link).toHaveAttribute('target', '_blank');
        expect(link).toHaveAttribute('rel', 'noopener noreferrer');
        expect(link).toHaveClass('text-blue-600', 'no-underline');
    });

    it('renders the table of contents as an unordered list', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        const tocNavigations = screen.getAllByRole('navigation', { name: 'Daftar Isi' });
        expect(tocNavigations.every((navigation) => navigation.querySelector('ul'))).toBe(true);
    });

    it('renders section links as unordered lists', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        const unorderedLists = screen.getAllByRole('list').filter((list) => list.tagName === 'UL');
        expect(unorderedLists.length).toBeGreaterThan(0);
    });

    it('shows an empty message for a section without links', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        expect(screen.getByText('Belum ada tautan pada section ini.')).toBeInTheDocument();
    });

    it('renders a skeleton while loading', async () => {
        let resolveGet: (value: { data: LinksPayload }) => void = () => undefined;
        vi.mocked(axios.get).mockReturnValue(
            new Promise((resolve) => {
                resolveGet = resolve;
            })
        );

        renderPage();

        expect(
            screen.getByRole('status', { name: /Memuat daftar tautan penting/ })
        ).toBeInTheDocument();

        resolveGet({ data: linksPayload });

        expect(await screen.findByRole('heading', { name: 'Tautan Penting' })).toBeInTheDocument();
    });

    it('spaces the mobile table of contents skeleton below the intro', async () => {
        vi.mocked(axios.get).mockReturnValue(new Promise(() => undefined));

        renderPage();

        const mobileToc = document.querySelector('.lg\\:hidden');
        expect(mobileToc).not.toBeNull();
        expect(mobileToc).toHaveClass('mt-10', 'md:mt-9');
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

    it('shows an empty state when there are no sections', async () => {
        vi.mocked(axios.get).mockResolvedValue({ data: { status: 'success', data: [] } });

        renderPage();

        expect(await screen.findByText('Belum ada tautan penting.')).toBeInTheDocument();
    });

    it('renders a Daftar Isi navigation in the mobile flow and the desktop sidebar', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        const navigations = screen.getAllByRole('navigation', { name: 'Daftar Isi' });
        expect(navigations).toHaveLength(2);
        expect(navigations.filter((navigation) => navigation.closest('aside'))).toHaveLength(1);
        expect(navigations.filter((navigation) => navigation.closest('.lg\\:hidden'))).toHaveLength(
            1
        );
        expect(screen.getAllByRole('button', { name: 'Kumpulan Link MBKM' })).toHaveLength(2);
        expect(screen.getAllByRole('button', { name: 'Akademik' })).toHaveLength(2);
    });

    it('gives each section heading an id and scroll margin for anchor navigation', async () => {
        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        const mbkmHeading = screen.getByRole('heading', { name: 'Kumpulan Link MBKM' });
        expect(mbkmHeading).toHaveAttribute('id', 'link-section-1');
        expect(mbkmHeading).toHaveClass('scroll-mt-28', 'md:scroll-mt-27');
        expect(screen.getByRole('heading', { name: 'Akademik' })).toHaveAttribute(
            'id',
            'link-section-2'
        );
    });

    it('scrolls to the section when a table of contents item is clicked', async () => {
        const scrollIntoView = vi.mocked(Element.prototype.scrollIntoView);
        scrollIntoView.mockClear();

        renderPage();

        await screen.findByRole('heading', { name: 'Tautan Penting' });

        screen.getAllByRole('button', { name: 'Kumpulan Link MBKM' })[0].click();

        expect(scrollIntoView).toHaveBeenCalledTimes(1);
        expect(scrollIntoView).toHaveBeenCalledWith({ behavior: 'smooth', block: 'start' });
        expect(scrollIntoView.mock.instances[0]).toBe(document.getElementById('link-section-1'));
    });
});
