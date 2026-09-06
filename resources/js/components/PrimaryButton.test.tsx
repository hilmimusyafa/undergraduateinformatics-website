import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { PrimaryButton } from './PrimaryButton';

describe('PrimaryButton', () => {
    it('renders a button with the default variant styling', () => {
        render(<PrimaryButton>Kirim</PrimaryButton>);

        const button = screen.getByRole('button', { name: 'Kirim' });
        expect(button).toBeInTheDocument();
    });

    it('forwards onClick handlers', () => {
        const onClick = vi.fn();

        render(<PrimaryButton onClick={onClick}>Kirim</PrimaryButton>);

        screen.getByRole('button', { name: 'Kirim' }).click();

        expect(onClick).toHaveBeenCalledTimes(1);
    });

    it('applies a custom className', () => {
        render(<PrimaryButton className="custom-class">Kirim</PrimaryButton>);

        expect(screen.getByRole('button', { name: 'Kirim' })).toHaveClass('custom-class');
    });
});
