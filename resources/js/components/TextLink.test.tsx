import { render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { TextLink } from './TextLink';

vi.mock('@tanstack/react-router', async () => {
    const actual =
        await vi.importActual<typeof import('@tanstack/react-router')>('@tanstack/react-router');

    return {
        ...actual,
        createLink: (Comp: any) =>
            function MockedLink({ to, ...props }: any) {
                return <Comp href={to} {...props} />;
            },
    };
});

describe('TextLink', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders a link with the given label', () => {
        render(
            <TextLink variant="fade" to="/">
                Kembali
            </TextLink>
        );

        expect(screen.getByRole('link', { name: 'Kembali' })).toBeInTheDocument();
    });

    it('renders the destination as the href', () => {
        render(
            <TextLink variant="fade" to="/feedback">
                Masukan
            </TextLink>
        );

        expect(screen.getByRole('link', { name: 'Masukan' })).toHaveAttribute('href', '/feedback');
    });

    it('applies a custom className', () => {
        render(
            <TextLink variant="underline" to="/" className="custom-class">
                Kembali
            </TextLink>
        );

        expect(screen.getByRole('link', { name: 'Kembali' })).toHaveClass('custom-class');
    });

    it('applies the fade hover styling for the fade variant', () => {
        render(
            <TextLink variant="fade" to="/">
                Kembali
            </TextLink>
        );

        const link = screen.getByRole('link', { name: 'Kembali' });
        expect(link).toHaveClass('hover:bg-transparent', 'hover:text-muted-foreground');
        expect(link).toHaveClass('text-foreground');
        expect(link).not.toHaveClass('bg-primary');
    });

    it('uses the link variant for the underline variant', () => {
        render(
            <TextLink variant="underline" to="/">
                Isi Formulir Lagi
            </TextLink>
        );

        const link = screen.getByRole('link', { name: 'Isi Formulir Lagi' });
        expect(link).toHaveClass('hover:underline', 'underline-offset-4');
    });
});
