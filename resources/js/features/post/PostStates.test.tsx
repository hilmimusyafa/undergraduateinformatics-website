import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { PostNotFound, PostSkeleton } from './PostStates';

function renderSkeleton() {
    return render(<PostSkeleton />);
}

function renderNotFound() {
    return render(<PostNotFound />);
}

describe('PostStates', () => {
    it('renders the loading skeleton with a status role', () => {
        renderSkeleton();

        expect(screen.getByRole('status', { name: 'Memuat detail informasi' })).toBeInTheDocument();
    });

    it('renders skeleton blocks in the content order (title, subtitle, image, body, meta)', () => {
        const { container } = renderSkeleton();
        const skeletons = container.querySelectorAll('[data-slot="skeleton"]');

        expect(skeletons).toHaveLength(7);
        expect(skeletons[0]).toHaveClass('h-9');
        expect(skeletons[1]).toHaveClass('h-7');
        expect(skeletons[2]).toHaveClass('w-full');
        expect(skeletons[3]).toHaveClass('h-5');
        expect(skeletons[4]).toHaveClass('h-5');
        expect(skeletons[5]).toHaveClass('h-5');
        expect(skeletons[6]).toHaveClass('w-1/2');
    });

    it('renders the post not-found message', () => {
        renderNotFound();

        expect(
            screen.getByRole('heading', { name: 'Informasi tidak ditemukan' })
        ).toBeInTheDocument();
        expect(
            screen.getByText('Informasi mungkin sudah dihapus atau alamatnya salah.')
        ).toBeInTheDocument();
    });
});
