import { render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { TopBar } from './TopBar';

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
        createLink: (Comp: any) =>
            function MockedLink({ to, ...props }: any) {
                return <Comp href={to} {...props} />;
            },
    };
});

describe('TopBar', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders navigation links', () => {
        render(<TopBar isSidebarOpen={false} onToggleSidebar={() => undefined} />);

        expect(screen.getByRole('link', { name: 'Beranda' })).toHaveAttribute('href', '/');
        expect(screen.getByRole('link', { name: 'Informasi' })).toHaveAttribute('href', '/tags');
        expect(screen.getByRole('link', { name: 'Tautan' })).toHaveAttribute('href', '/links');
        expect(screen.getByRole('link', { name: 'Masukan' })).toHaveAttribute('href', '/feedback');
        expect(screen.getByRole('link', { name: 'Pertemuan' })).toHaveAttribute(
            'href',
            '/reservation'
        );
    });

    it('renders the sign-in link', () => {
        render(<TopBar isSidebarOpen={false} onToggleSidebar={() => undefined} />);

        expect(screen.getByRole('link', { name: 'Masuk' })).toHaveAttribute('href', '/');
    });

    it('renders the sidebar toggle button on small screens', () => {
        render(<TopBar isSidebarOpen={false} onToggleSidebar={() => undefined} />);

        expect(screen.getByRole('button', { name: 'Toggle navigation menu' })).toBeInTheDocument();
    });

    it('calls onToggleSidebar when the toggle button is clicked', () => {
        const onToggleSidebar = vi.fn();

        render(<TopBar isSidebarOpen={false} onToggleSidebar={onToggleSidebar} />);

        screen.getByRole('button', { name: 'Toggle navigation menu' }).click();

        expect(onToggleSidebar).toHaveBeenCalledTimes(1);
    });
});
