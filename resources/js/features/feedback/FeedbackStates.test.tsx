import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { FeedbackSkeleton } from './FeedbackStates';

describe('FeedbackSkeleton', () => {
    it('renders a loading status region', () => {
        render(<FeedbackSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat formulir/ })).toBeInTheDocument();
    });

    it('renders question sections', () => {
        const { container } = render(<FeedbackSkeleton />);

        expect(container.querySelectorAll('section')).toHaveLength(2);
    });

    it('keeps the constrained article width', () => {
        render(<FeedbackSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat formulir/ })).toHaveClass('max-w-[37em]');
    });
});
