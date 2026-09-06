import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { PostCardSkeleton } from './PostCardStates';

describe('PostCardSkeleton', () => {
    it('renders a status region announcing the loading state', () => {
        render(<PostCardSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat/ })).toBeInTheDocument();
    });

    it('renders placeholders for the title, subtitle, tags, and date rows', () => {
        const { container } = render(<PostCardSkeleton />);

        expect(container.querySelectorAll('[data-slot="skeleton"]')).toHaveLength(5);
    });

    it('isolates the skeleton from the typeset typography styles', () => {
        render(<PostCardSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat/ })).toHaveClass('not-typeset');
    });
});
