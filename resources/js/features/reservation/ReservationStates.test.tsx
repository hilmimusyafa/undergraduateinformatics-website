import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { ReservationSkeleton } from './ReservationStates';

describe('ReservationSkeleton', () => {
    it('renders a loading status region', () => {
        render(<ReservationSkeleton />);

        expect(
            screen.getByRole('status', { name: /Memuat formulir reservasi/ })
        ).toBeInTheDocument();
    });

    it('renders question sections', () => {
        const { container } = render(<ReservationSkeleton />);

        expect(container.querySelectorAll('section')).toHaveLength(4);
    });

    it('keeps the constrained article width', () => {
        render(<ReservationSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat formulir reservasi/ })).toHaveClass(
            'max-w-[37em]'
        );
    });
});
