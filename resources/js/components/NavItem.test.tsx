import { render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { NavItem } from './NavItem';

vi.mock('@tanstack/react-router', async () => {
    const actual =
        await vi.importActual<typeof import('@tanstack/react-router')>('@tanstack/react-router');

    return {
        ...actual,
        Link: ({ to, className, children }: any) => (
            <a href={to} className={className}>
                {children}
            </a>
        ),
    };
});

describe('NavItem', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders a link with the top variant styling', () => {
        render(
            <NavItem variant="top" to="/feedback">
                Masukan
            </NavItem>
        );

        const link = screen.getByRole('link', { name: 'Masukan' });
        expect(link).toHaveAttribute('href', '/feedback');
        expect(link.className).toContain('py-1.5');
        expect(link.className).toContain('underline-offset-10');
        expect(link.className).toContain('data-[status=active]:underline');
    });

    it('renders a link with the side variant styling', () => {
        render(
            <NavItem variant="side" to="/feedback">
                Masukan
            </NavItem>
        );

        const link = screen.getByRole('link', { name: 'Masukan' });
        expect(link).toHaveAttribute('href', '/feedback');
        expect(link.className).toContain('justify-start');
        expect(link.className).toContain('w-full');
    });

    it('passes extra props and className through to the link', () => {
        render(
            <NavItem variant="side" to="/explore" className="custom-class" data-testid="nav">
                Informasi
            </NavItem>
        );

        const link = screen.getByRole('link', { name: 'Informasi' });
        expect(link).toHaveAttribute('href', '/explore');
        expect(link).toHaveClass('custom-class');
    });
});
