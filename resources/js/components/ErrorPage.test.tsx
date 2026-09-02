import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { ErrorPage } from './ErrorPage';

describe('ErrorPage', () => {
    it('renders the error title and description', () => {
        render(<ErrorPage />);

        expect(screen.getByRole('heading', { name: 'Terjadi kesalahan' })).toBeInTheDocument();
        expect(
            screen.getByText('Maaf, ada yang tidak beres. Silakan muat ulang halaman ini.')
        ).toBeInTheDocument();
    });

    it('renders the raw error message when provided', () => {
        render(<ErrorPage error={{ message: 'Gagal memuat data' }} />);

        expect(screen.getByText('Gagal memuat data')).toBeInTheDocument();
    });
});
