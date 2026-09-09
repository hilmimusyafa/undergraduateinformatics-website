import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { LinkCardSkeleton } from './LinkCardStates';

describe('LinkCardSkeleton', () => {
    it('renders a status region announcing the loading state', () => {
        render(<LinkCardSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat/ })).toBeInTheDocument();
    });

    it('renders placeholders for the title, section, and date rows', () => {
        const { container } = render(<LinkCardSkeleton />);

        expect(container.querySelectorAll('[data-slot="skeleton"]')).toHaveLength(4);
    });

    it('isolates the skeleton from the typeset typography styles', () => {
        render(<LinkCardSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat/ })).toHaveClass('not-typeset');
    });
});
