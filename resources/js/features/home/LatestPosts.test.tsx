import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { type PostSummary } from '@/types/post';

import { LatestPosts } from './LatestPosts';

vi.mock('@/components/PostCard', () => ({
    PostCard: () => <article data-testid="post-card" />,
}));

vi.mock('@tanstack/react-router', async () => {
    const { routerModuleMock } = await import('@/test/mocks');

    return routerModuleMock();
});

const post: PostSummary = {
    id: 7,
    slug: 'pengumuman-beasiswa-2026',
    title: 'Pengumuman Beasiswa 2026',
    subtitle: 'Pendaftaran beasiswa dibuka hingga akhir bulan.',
    updated_at: '2026-09-05T12:00:00.000Z',
    tags: [{ id: 1, slug: 'beasiswa', name: 'Beasiswa' }],
};

function posts(count: number): PostSummary[] {
    return Array.from({ length: count }, (_, index) => ({
        ...post,
        id: index + 1,
    }));
}

describe('LatestPosts', () => {
    it('renders the section heading', () => {
        render(<LatestPosts posts={posts(1)} />);

        expect(screen.getByRole('heading', { name: 'Informasi Terbaru' })).toBeInTheDocument();
    });

    it('links the heading to the search page', () => {
        render(<LatestPosts posts={posts(1)} />);

        const link = screen.getByRole('link', { name: 'Informasi Terbaru' });
        expect(link).toHaveAttribute('href', '/posts/search');
    });

    it('renders a PostCard per post', () => {
        render(<LatestPosts posts={posts(3)} />);

        expect(screen.getAllByTestId('post-card')).toHaveLength(3);
    });

    it('renders an empty message when there are no posts', () => {
        render(<LatestPosts posts={[]} />);

        expect(screen.getByText('Belum ada berita atau pengumuman.')).toBeInTheDocument();
        expect(screen.queryByTestId('post-card')).not.toBeInTheDocument();
    });
});
