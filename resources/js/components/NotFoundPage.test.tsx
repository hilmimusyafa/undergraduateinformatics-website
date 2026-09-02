import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { NotFoundPage } from './NotFoundPage';
import { RouterHarness } from './RouterHarness';

const { navigateMock } = vi.hoisted(() => ({ navigateMock: vi.fn() }));

vi.mock('@tanstack/react-router', async () => {
    const actual =
        await vi.importActual<typeof import('@tanstack/react-router')>('@tanstack/react-router');

    return {
        ...actual,
        useNavigate: () => navigateMock,
    };
});

describe('NotFoundPage', () => {
    it('renders the 404 heading and description', async () => {
        render(
            <RouterHarness>
                <NotFoundPage />
            </RouterHarness>
        );

        expect(
            await screen.findByRole('heading', { name: 'Halaman Tidak Ditemukan' })
        ).toBeInTheDocument();
        expect(screen.getByText(/Alamat mungkin salah/)).toBeInTheDocument();
    });

    it('navigates to the home page when the button is clicked', async () => {
        render(
            <RouterHarness>
                <NotFoundPage />
            </RouterHarness>
        );

        (await screen.findByRole('button', { name: 'Kembali ke Beranda' })).click();

        expect(navigateMock).toHaveBeenCalledWith({ to: '/' });
    });
});
