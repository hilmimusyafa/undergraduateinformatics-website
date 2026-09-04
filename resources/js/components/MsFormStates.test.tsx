import { render, screen } from '@testing-library/react';
import { describe, expect, it, vi } from 'vitest';

import { MsFormError, MsFormSkeleton, MsFormSuccess, MsFormUnavailable } from './MsFormStates';

describe('MsFormSkeleton', () => {
    it('renders a loading status region', () => {
        render(<MsFormSkeleton />);

        expect(screen.getByRole('status', { name: /Memuat formulir/ })).toBeInTheDocument();
    });

    it('renders question sections', () => {
        const { container } = render(<MsFormSkeleton />);

        expect(container.querySelectorAll('section')).toHaveLength(2);
    });
});

describe('MsFormUnavailable', () => {
    it('renders an unavailable message', () => {
        render(<MsFormUnavailable />);

        expect(screen.getByRole('status')).toHaveTextContent(/Formulir sedang tidak tersedia/);
    });
});

describe('MsFormError', () => {
    it('renders an alert with a retry message', () => {
        render(<MsFormError />);

        expect(screen.getByRole('alert')).toHaveTextContent(
            'Terjadi kesalahan saat memuat formulir. Silakan coba lagi.'
        );
    });
});

describe('MsFormSuccess', () => {
    it('renders a confirmation and a reset link', () => {
        const onReset = vi.fn();

        render(<MsFormSuccess onReset={onReset} />);

        expect(screen.getByRole('heading', { name: 'Terima kasih!' })).toBeInTheDocument();
        expect(screen.getByText('Formulir Anda telah berhasil dikirim.')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /Isi Formulir Lagi/ })).toBeInTheDocument();
    });

    it('calls onReset when the reset link is clicked', async () => {
        const onReset = vi.fn();

        render(<MsFormSuccess onReset={onReset} />);

        screen.getByRole('button', { name: /Isi Formulir Lagi/ }).click();

        expect(onReset).toHaveBeenCalledTimes(1);
    });
});
