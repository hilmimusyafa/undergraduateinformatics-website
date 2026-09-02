import { render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { SideBar } from './SideBar';

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

describe('SideBar', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders navigation links', () => {
        render(<SideBar isOpen onClose={() => undefined} />);

        expect(screen.getByRole('link', { name: 'Beranda' })).toHaveAttribute('href', '/');
        expect(screen.getByRole('link', { name: 'Informasi' })).toHaveAttribute('href', '/tags');
        expect(screen.getByRole('link', { name: 'Tautan' })).toHaveAttribute('href', '/links');
        expect(screen.getByRole('link', { name: 'Masukan' })).toHaveAttribute('href', '/feedback');
        expect(screen.getByRole('link', { name: 'Pertemuan' })).toHaveAttribute(
            'href',
            '/reservation'
        );
        expect(screen.getByRole('link', { name: 'Masuk' })).toHaveAttribute('href', '/reservation');
    });

    it('renders a search bar', () => {
        render(<SideBar isOpen onClose={() => undefined} />);

        expect(screen.getByPlaceholderText('Cari...')).toBeInTheDocument();
    });

    it('is translated off-screen when closed', () => {
        const { container } = render(<SideBar isOpen={false} onClose={() => undefined} />);

        expect(container.querySelector('aside')?.className).toContain('-translate-x-full');
    });

    it('calls onClose when a nav link is clicked', () => {
        const onClose = vi.fn();

        render(<SideBar isOpen onClose={onClose} />);

        screen.getByRole('link', { name: 'Beranda' }).click();

        expect(onClose).toHaveBeenCalledTimes(1);
    });
});
