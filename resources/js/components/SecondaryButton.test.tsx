import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { SecondaryButton } from './SecondaryButton';

describe('SecondaryButton', () => {
    it('renders a button with the outline variant styling', () => {
        render(<SecondaryButton>Kembali</SecondaryButton>);

        const button = screen.getByRole('button', { name: 'Kembali' });
        expect(button).toBeInTheDocument();
    });

    it('forwards onClick handlers', () => {
        const onClick = vi.fn();

        render(<SecondaryButton onClick={onClick}>Kembali</SecondaryButton>);

        screen.getByRole('button', { name: 'Kembali' }).click();

        expect(onClick).toHaveBeenCalledTimes(1);
    });

    it('applies a custom className', () => {
        render(<SecondaryButton className="custom-class">Kembali</SecondaryButton>);

        expect(screen.getByRole('button', { name: 'Kembali' })).toHaveClass('custom-class');
    });
});
