import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { type LinkSummary } from '@/types/link';

import { LatestLinks } from './LatestLinks';

vi.mock('@tanstack/react-router', async () => {
    const { routerModuleMock } = await import('@/test/mocks');

    return routerModuleMock();
});

vi.mock('@/components/LinkCard', () => ({
    LinkCard: () => <article data-testid="link-card" />,
}));

const link: LinkSummary = {
    id: 7,
    name: 'Portal Akademik',
    link: 'https://portal.telkomuniversity.ac.id/',
    updated_at: '2026-09-05T12:00:00.000Z',
    section: { id: 2, name: 'Akademik' },
};

function links(count: number): LinkSummary[] {
    return Array.from({ length: count }, (_, index) => ({
        ...link,
        id: index + 1,
    }));
}

describe('LatestLinks', () => {
    it('renders the section heading', () => {
        render(<LatestLinks links={links(1)} />);

        expect(screen.getByRole('heading', { name: 'Tautan Terbaru' })).toBeInTheDocument();
    });

    it('links the heading to the links page', () => {
        render(<LatestLinks links={links(1)} />);

        const link = screen.getByRole('link', { name: 'Tautan Terbaru' });
        expect(link).toHaveAttribute('href', '/links');
    });

    it('renders a LinkCard per link', () => {
        render(<LatestLinks links={links(3)} />);

        expect(screen.getAllByTestId('link-card')).toHaveLength(3);
    });

    it('renders an empty message when there are no links', () => {
        render(<LatestLinks links={[]} />);

        expect(screen.getByText('Belum ada tautan penting.')).toBeInTheDocument();
        expect(screen.queryByTestId('link-card')).not.toBeInTheDocument();
    });
});
