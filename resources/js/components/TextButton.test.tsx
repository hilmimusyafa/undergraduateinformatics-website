import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { TextButton } from './TextButton';

describe('TextButton', () => {
    it('renders a button with the given label', () => {
        render(<TextButton variant="fade">Kembali</TextButton>);

        expect(screen.getByRole('button', { name: 'Kembali' })).toBeInTheDocument();
    });

    it('forwards onClick handlers', () => {
        const onClick = vi.fn();

        render(
            <TextButton variant="fade" onClick={onClick}>
                Kembali
            </TextButton>
        );

        screen.getByRole('button', { name: 'Kembali' }).click();

        expect(onClick).toHaveBeenCalledTimes(1);
    });

    it('applies a custom className', () => {
        render(
            <TextButton variant="underline" className="custom-class">
                Kembali
            </TextButton>
        );

        expect(screen.getByRole('button', { name: 'Kembali' })).toHaveClass('custom-class');
    });

    it('applies the fade hover styling for the fade variant', () => {
        render(<TextButton variant="fade">Kembali</TextButton>);

        const button = screen.getByRole('button', { name: 'Kembali' });
        expect(button).toHaveClass('hover:bg-transparent', 'hover:text-muted-foreground');
        expect(button).toHaveClass('text-foreground');
        expect(button).not.toHaveClass('bg-primary');
    });

    it('uses the link variant for the underline variant', () => {
        render(<TextButton variant="underline">Isi Formulir Lagi</TextButton>);

        const button = screen.getByRole('button', { name: 'Isi Formulir Lagi' });
        expect(button).toHaveClass('hover:underline', 'underline-offset-4');
    });
});
